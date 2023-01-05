# Bili_Video

## 技术栈

- PHP 8.0（7.4）

## How to use

1. 首先你得在config.ini里进行一个cookie的配置
2. 其次你得把它上传到一台2c2g以上的super server（否则你将享受10s+的超快解析时间）
3. 然后你得进行一个php环境的配
4. 接着你得跟着这个文档进行一个请求的学
5. 最后你要进行一个请求的发

## 请求参数

请求Url: `GET HTTP/1.1 https://domain/path/to/bilibili.php`

### 参数: 

| 参数 | 是否必须 | 支持类型 / 注解 |
| -----| ---- | ---- |
| id | 和下面二选一 | BV / AV 号 （大小写不敏感） |
| url | 和上面二选一 | 视频链接，支持 b23.tv / m / www |
| format | 可选 | json（默认返回） / url（直接跳转链接） |

### config.ini 文件:

#### Request 部分：

cookie就是上文提到的填写cookie处，F12的网络里找第一个请求翻到cookie那块复制SESSDATA和_uuid这两串数据（实测只有uuid那一段解析不出720p以上，估计改了）

ua和header是请求b站api时用到的，默认忽略就行

#### Common 部分：

DisableCache 默认设置为false，如果需要让你的server不缓存这个page那你得进行一个配置的改，把false改成true（不会还有人不知道这两个boolean值吧）

### 返回示例：

若一切正确，请求后你会得到这样一串json：

```
{
    "code": 1,
    "msg": "解析成功！",
    "title": "最终鬼畜蓝蓝路",
    "imgurl": "http://i2.hdslb.com/bfs/archive/34d8fdf08d1fe28c229dec2fd122815a1d012908.jpg",
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
