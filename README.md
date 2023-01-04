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

cookie就是上文提到的填写cookie处，具体格式为_uuid=XXXXX （一大坨cookie里就只要这一段），不填就没有1080p的选项了

ua和header是请求b站api时用到的，默认忽略就行

#### Common 部分：

DisableCache 默认设置为false，如果需要让你的server不缓存这个page那你得进行一个配置的改，把false改成true（不会还有人不知道这两个boolean值吧）

## 一些话的说

怎么样，是不是非常简单呢（笑）

其实吧我这个还是有很大的进步空间，就看有没有人能去厕所把这坨屎山捞出来进行一个修的改

很多小细节可能处理的不是很好，看到了可以提个pr或issue

感谢 [苏晓晴](https://github.com/Suxiaoqinx/) 的源代码

<p align="center">Based on 苏晓晴,Powered by 拜之所瑞.</p>
