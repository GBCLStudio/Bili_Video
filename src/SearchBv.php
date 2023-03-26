<?php
/*
 * This file is part of GBCLStudio Project.
 *
 * Copyright (c) GBCLStudio PHP Project Team.
 * All Rights Reserved.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * Copyright (c) GBCLStudio PHP Project Team 2021-2023.
 */

namespace GBCLStudio;

class SearchBv
{
    private string $cookie;
    /**
     * @var array|string[]
     */
    private array $header;
    private string $ua;

    private string|array $query;

    /** 构造器
     *
     * @param array $accountInfo
     * @param string|array $queryVideo
     *
     */
    public function __construct(array $accountInfo, string|array $queryVideo)
    {
        $cookie = $accountInfo[0];
        $header = $accountInfo[1] ?? 'Content-type: application/json;charset=UTF-8';
        $useragent = $accountInfo[2];

        $this->cookie = $cookie;
        $this->header = $header;
        $this->ua = $useragent;
        $this->query =  $queryVideo;

        header('Content-type: text/json;charset=utf-8');
        header('Pragma:no-cache,no-store');
        header('Cache-Control:no-cache,must-revalidate,no-store');
    }

    /** 根据传入参数决定是直接进行查询还是遍历过后再查询
     *
     * @return array|bool|string|string[]
     */
    public function searchVideo(): array|bool|string
    {

        if (is_array($this->query)){
            return $this->searchMultiVideo();
        }else return $this->doSearchVideoHandle($this->query);

    }

    /**
     * @param string $video
     * @return mixed|string|string[]|void
     */
    public function getBvid(string $video){
        if (filter_var($video,FILTER_VALIDATE_URL)) {
            global $bvid;
            $array = parse_url($video);
            if ($array['host'] == 'b23.tv') { //处理短链接
                $header = get_headers($video, true);
                $array = parse_url($header['Location'][0]);
                $bvid = $array['path'];
            } elseif ($array['host'] == 'www.bilibili.com') { //处理www
                $bvid = $array['path'];
            } elseif ($array['host'] == 'm.bilibili.com') { //处理m站
                $bvid = $array['path'];
            }
            if (stristr($bvid,'/video/')) {
                $bvid = str_replace("/video/", "", $bvid);
            }else{
                exit(json_encode(['code' => '-1','msg' => '输入参数有误！'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
            }

        } elseif (stristr($video, "av")) {
            $avid = explode("av", strtolower($video));
            $bvid = $this->av2bv($avid[1]);
        } elseif (stristr($video, "bv")) {
            $bvid = $video;
        } else{
            exit(json_encode(['code' => '-1','msg' => 'WrongParameter'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        }
        return $bvid;
    }

    /** 核心查询进程
     *
     * @return array|string[]
     */
    public function doSearchVideoHandle(string $video): array
    {
        global $bilijson;
        global $bvid;
        global $array;

        $bvid = $this->getBvid($video);

        //获取解析需要的cid值和图片以及标题
        $json1 = $this->curl(
            'https://api.bilibili.com/x/web-interface/view?bvid=' . $bvid
            , $this->header
            , $this->ua
            , $this->cookie
        );
        $array = json_decode($json1, true);
        if ($array['code'] == '0') {
            global $array_2;
            global $pron;
            //循环获取
            foreach ($array['data']['pages'] as $pron) {
                //对接上面获取cid值API来取得视频的直链
                $json2 = $this->curl(
                    "https://api.bilibili.com/x/player/playurl?otype=json&fnver=0&fnval=3&player=3&qn=64&bvid=" . $bvid . "&cid=" . $pron['cid'] . "&platform=html5&high_quality=2"
                    , $this->header
                    , $this->ua
                    , $this->cookie
                );
                $array_2 = json_decode($json2, true);
            }
            /**
             * 重新构建能够直接访问的url
             * 如 upos-sz-mirror[hw/ali/cos].bilivideo.com 等
             */
            $brokeUrl = parse_url($array_2['data']['durl'][0]['url']); //获取并解析带有防盗链的视频url
            $video_host = [ //咱就是说只有一个域名怎么行呢
                "upos-sz-mirrorali",
                "upos-sz-mirroralib",
                "upos-sz-mirroralio1",
                "upos-sz-mirroraliov",
                "upos-sz-mirrorcos",
                "upos-sz-mirrorcosov",
                "upos-sz-mirrorcoso1",
                "upos-sz-mirrorcosb",
                "upos-sz-estgoss",
                "upos-sz-estgoss02",
                "upos-sz-mirrorhw",
                "upos-sz-mirrorhwo1",
                "upos-sz-mirrorhwb",
                "upos-sz-mirrorhwdisp",
                "upos-tf-all-hw",
                "upos-sz-mirrorks3c",
                "upos-sz-mirror08ct"
            ];
            shuffle($video_host); //打乱数组
            $rand_host = array_rand($video_host, 1); //随便rand一下
            $video_url = 'https://' . $video_host[$rand_host] . '.bilivideo.com' . $brokeUrl['path'] . '?' . $brokeUrl['query'];
            $bilijson = [
                'title' => $pron['part']
                , 'duration' => $pron['duration']
                , 'durationFormat' => gmdate('H:i:s', $pron['duration'] - 1)
                , 'accept' => $array_2['data']['accept_description']
                , 'video_url' => $video_url
            ]; //构建视频信息数组
            return [
                'code' => 1
                , 'msg' => 'success'
                , 'title' => $array['data']['title']
                , 'imgurl' => $array['data']['pic']
                , 'desc' => $array['data']['desc']
                , 'data' => $bilijson
                , 'user' => [
                    'name' => $array['data']['owner']['name']
                    , 'user_img' => $array['data']['owner']['face']
                ]
            ]; //构建json数组
        } elseif($bilijson['video_url'] == null) {
            return ['code' => '-1','msg' => 'VideoNotFound'];
        } else {
            return ['code' => 0, 'msg' => "ParsingFailed"]; //获取失败
        }
    }

    /** 如果传入为数组，则进行遍历
     *
     * @return array|bool|string
     */
    private function searchMultiVideo(): array|bool|string
    {
        //为了保证查询速度
        if (count($this->query) > 4) {
            return ['code' => '-1','msg' => 'NotSupported'];
        }
        $result = [];
        foreach ($this->query as $item) {
            $result[] = $this->doSearchVideoHandle($item);
        }
        return $result;
    }

    /** convert bvid from passing avid
     * @param $av
     * @return string
     */
    private function av2bv($av): string
    {
        $r=['B','V','1',' ',' ','4',' ','1',' ','7',' ',' '];
        foreach ($r as $i=>$v) {
            if($i>=6) break;
            $r[[11,10,3,8,4,6][$i]]='fZodR9XQDSUm21yCkr6zBqiveYah8bt4xsWpHnJE7jL5VG3guMTKNPAwcF'[floor(((($av^177451812)+8728348608)/pow(58,$i)))%58];
        }
        return join("", $r);
    }

    /**
     * @param $url
     * @param array $header
     * @param string $ua
     * @param string $cookie
     * @return bool|string
     */
    private function curl($url, array $header=array(""), string $ua='Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36', string $cookie=''): bool|string
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_USERAGENT,$ua);
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $content=curl_exec($ch);
        curl_close($ch);
        return $content;
    }
}
