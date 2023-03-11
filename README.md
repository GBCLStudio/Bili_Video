# Bili_Video

## 技术栈

- PHP 8.x

## How to use

通过 Composer 安装：

```shell
composer require gbcl/searchbv
```

调用示范：

```php
use GBCLStudio\SearchBv;

$AccountsArr = array('_uuid=; SESSDATA=','Content-type: application/json;charset=UTF-8','Mozilla/5.0 (balabala)'); // array(cookie,Content-type,UserAgent)
$queryVideo = array('av1919810', 'BV1xx411c7mu'); // or $queryVideo = 'BV1FD4y1776T';
$searchApi = new GBCLStudio\SearchBv($AccountsArr,$queryVideo);
echo json_encode($searchApi->searchVideo(),480);
```

### 返回示例：

若一切正确，你会得到这样一串json：

```
{
    "code": 1,
    "msg": "解析成功！",
    "title": "最终鬼畜蓝蓝路",
    "imgurl": "https://i2.hdslb.com/bfs/archive/34d8fdf08d1fe28c229dec2fd122815a1d012908.jpg",
    "desc": "sm2057168 把这个音mad的图腾和支柱从UP的怒火中搬出来重新立好，这是我所能做的最后的事情了。", //简介
    "data": {
        "title": "",
        "duration": 318, //这俩忘了
        "durationFormat": "00:05:17", //时长
        "accept": [
            "流畅 360P" //清晰度
        ],
        "video_url": "https://upos-sz-mirror08ct.bilivideo.com/upgcxcode/63/58/3635863/3635863_da3-1-16.mp4?e=ig8euxZM2rNcNbRVhwdVhwdlhWdVhwdVhoNvNC8BqJIzNbfq9rVEuxTEnE8L5F6VnEsSTx0vkX8fqJeYTj_lta53NCM=&uipk=5&nbs=1&deadline=1672852291&gen=playurlv2&os=bcache&oi=730840916&trid=0000a18c2d9a5c50444c8d14ffac381a6274h&mid=0&platform=html5&upsig=aa2bbbdb019d51d54980abc7fdb5631f&uparams=e,uipk,nbs,deadline,gen,os,oi,trid,mid,platform&cdnid=3843&bvc=vod&nettype=0&bw=51310&logo=80000000" //视频url（本体）
    },
    "user": { //up主相关
        "name": "TSA",
        "user_img": "http://i1.hdslb.com/bfs/face/0ef5daf622bf4789034b3c15147a45e11c48c9b3.jpg"
    }
}
```

返回不是这样的自己排查去，这里没有troubleshooting

## 一些话的说

怎么样，是不是非常简单呢（笑）

其实吧我这个还是有很大的进步空间，就看有没有人能去厕所把这坨屎山捞出来进行一个修的改

很多小细节可能处理的不是很好，看到了可以提个pr或issue

感谢 [苏晓晴](https://github.com/Suxiaoqinx/) 的源代码

<p align="center">Based on 苏晓晴,Powered by 拜之所瑞.</p>
