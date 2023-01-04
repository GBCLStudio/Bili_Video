<?php
/**
 * 基于苏晓晴版本修改并增加稳定性
 * (c) GBCLStudio
 */

/**
  * config区
  */
$config = parse_ini_file("config.ini",true);

$cookie = $config['Request']['cookie'];
$header = $config['Request']['header'];
$useragent = $config['Request']['header'];

/**
  * 是否开启缓存
  */
if ($config['common']['DisableCache'] == true){
header('Content-type: text/json;charset=utf-8');
header('Pragma:no-cache,no-store');
header('Cache-Control:no-cache,must-revalidate,no-store');
}

/** 几个小示例
 * $urls = 'https://b23.tv/3ygbgeA';
 * $bv = 'BV1XT411A7HF';
 */

$urls = $_GET['url'];
$bv = $_GET['id'];
$returnType = $_GET['type'];
$array = parse_url($urls);

/**
  * 解析原视频链接 or bv号
  */
if (empty($array) and empty($bv)) { //默认empty的返回
    exit(json_encode(['code'=>-1, 'msg'=>"输入参数不正确"], 480));
}elseif ($array['host'] == 'b23.tv') { //处理短链接
    $header = get_headers($urls,true);
    $array = parse_url($header['Location']);
    $bvid = $array['path'];
}elseif ($array['host'] == 'www.bilibili.com') { //处理www
    $bvid = $array['path'];
}elseif ($array['host'] == 'm.bilibili.com') { //处理m站
    $bvid = $array['path'];
}elseif (!empty($bv)) { //处理bv号
    if (stristr($bv,"av") !== false)
    {
        $avid = explode("av",strtolower($bv)); //任何大小写敏感，终将，绳之以法！
        $QueryBvUrl = bilibili('http://api.bilibili.com/x/web-interface/archive/stat?aid=' . $avid[1]
                              , $header
                              , $useragent
                              , $cookie
                              );
        $QueryArray = json_decode($QueryBvUrl);
        $result = $QueryArray->{'data'}->{'bvid'};
        $bvid = $result;
    }else{
        $bvid = $bv;
    }
}else{
    exit(json_encode(['code'=>-1, 'msg'=>"参数好像不太对！"], 480));
}


$bvid = str_replace("/video/", "", $bvid);

/**
  * 获取解析需要的cid值和图片以及标题
  */
$json1 = bilibili(
    'https://api.bilibili.com/x/web-interface/view?bvid='.$bvid
    , $header
    , $useragent
    , $cookie
);
$array = json_decode($json1,true);
if($array['code'] == '0'){
    //循环获取
    foreach($array['data']['pages'] as $keys =>$pron){
        //对接上面获取cid值API来取得视频的直链
        $json2 = bilibili(
            "https://api.bilibili.com/x/player/playurl?otype=json&fnver=0&fnval=3&player=3&qn=64&bvid=".$bvid."&cid=".$pron['cid']."&platform=html5&high_quality=1"
            , $header
            , $useragent
            , $cookie
        );
        $array_2 = json_decode($json2,true);
      
        /**
         * 重新构建能够直接访问的url
         * 如 upos-sz-mirror[hw/ali/cos].bilibilivideo.com 等
         */
        $badurl = parse_url($array_2['data']['durl'][0]['url']); //获取并解析带有防盗链的视频url
        $video_host = array( //咱就是说只有一个域名怎么行呢
            "upos-sz-mirrorali",
            "upos-sz-mirroralib",
            "upos-sz-mirroralio1",
            "upos-sz-mirroraliov",
            "upos-sz-mirrorcos",
            "upos-sz-mirrorcosov",
            "upos-sz-mirrorcoso1",
            "upos-sz-mirrorcosb",
            "upos-sz-mirrorhw",
            "upos-sz-mirrorhwo1",
            "upos-sz-mirrorhwb",
            "upos-sz-mirrorhwdisp",
            "upos-tf-all-hw",
            "upos-sz-mirror08ct"
        );
        shuffle($video_host); //打乱数组
        $rand_host = array_rand($video_host,1); //随便rand一下
        $video_url = 'https://'. $video_host[$rand_host] . '.bilivideo.com' . $badurl['path'] . '?' . $badurl['query'];
        $bilijson = array(
            'title' =>  $pron['part']
            ,'duration' => $pron['duration']
            ,'durationFormat' => gmdate('H:i:s', $pron['duration']-1)
            ,'accept' => $array_2['data']['accept_description']
            ,'video_url' => $video_url
        ); //构建视频信息数组
    }
    $JSON = array(
        'code' => 1
        ,'msg' => '解析成功！'
        ,'title' => $array['data']['title']
        ,'imgurl' => $array['data']['pic']
        ,'desc' => $array['data']['desc']
        ,'data' => $bilijson
        ,'user' => [
            'name' => $array['data']['owner']['name']
            , 'user_img' => $array['data']['owner']['face']
        ]
    ); //构建json数组
}else{
    $JSON = ['code'=>0, 'msg'=>"解析失败！"]; //获取失败
}

//选择返回方式
if ($_GET['format'] == 'json' | $_GET['format'] == null) {
    echo json_encode($JSON, 480); //默认&json
}elseif ($_GET['format'] == 'url'){
    header('Location: ' . $video_url); //跳转url
}else{
    echo json_encode($JSON, 480); //任意
}
function bilibili($url, $header, $user_agent, $cookie) { //curl
    $ch = curl_init() ;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ;
    curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
    curl_setopt($ch, CURLOPT_USERAGENT,$user_agent);
    curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ;
    $output = curl_exec($ch) ;
    curl_close ($ch);
    return $output;
}
?>
