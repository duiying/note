# QQ登录实现流程
```
1. 去QQ互联(https://connect.qq.com)创建应用(网站应用/移动应用)
2. 填写域名/回调地址/备案等信息, 得到appid和appkey
3. 下载SDK, 解压到localhost, 访问http://localhost/Connect2.1/install/index.php
```
![qqlogin](https://raw.githubusercontent.com/duiying/note/master/img/qqlogin.png)  
```
4. 在项目的vendor目录中新建qqlogin目录, 将SDK包中相关文件和文件夹拷贝进去, 目录结构如下
qqlogin
    class
	comm
	qqConnectAPI.php
```


