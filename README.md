# Bili_Video

## 技术栈

- PHP 8.0

## How to use

1. 首先你得在config.ini里进行一个cookie的配置
2. 其次你得把它上传到一台2c2g以上的super server（否则你将享受10s+的超快解析时间）
3. 接着你得跟着这个文档进行一个请求的学
4. 最后你要进行一个请求的发

## 请求参数

请求Url: `GET HTTP/1.1 https://domain/path/to/bilibili.php`

参数: 

| 参数 | 是否必须 | 支持类型 |
| -----| ---- | ---- |
| id | 和下面二选一 | BV / AV 号（大小写不敏感） |
| url | 和上面二选一 | 视频链接，支持 b23.tv / m / www |
| format | 可选 | json（默认返回） / url（直接跳转链接） |

## 一些话的说

怎么样，是不是非常简单呢（笑）

其实吧我这个还是有很大的进步空间，就看有没有人能去厕所把这坨屎山捞出来进行一个修的改

很多小细节可能处理的不是很好，看到了可以提个pr或issue

感谢 [苏晓晴](https://github.com/Suxiaoqinx/) 的源代码

<p align="center">Based on 苏晓晴,Powered by 拜之所瑞.</p>
