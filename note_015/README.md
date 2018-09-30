# 搭建java环境以及配置renren-fast开源项目
### java环境搭建
安装jdk1.8
```
官网下载jdk安装包,这里我下载的是 jdk-8u181-windows-x64.exe
运行exe,修改jdk安装目录为 C:\Java\jdk1.8.0_181\ ,修改jre安装目录为 C:\Java\jre1.8.0_181\
``` 
添加java环境变量  
![jdkpath](https://raw.githubusercontent.com/duiying/note/master/img/jdkpath.png)
```
* 在cmd下执行 java -version 查看jdk版本
C:\Users\wyx>java -version
java version "1.8.0_181"
Java(TM) SE Runtime Environment (build 1.8.0_181-b13)
Java HotSpot(TM) 64-Bit Server VM (build 25.181-b13, mixed mode)
```
### maven安装
```
官网下载maven安装包,这里我下载的是apache-maven-3.3.9-bin.zip
解压到 C:\kfrj\apache-maven-3.3.9
```
添加环境变量  
![mavenpath](https://raw.githubusercontent.com/duiying/note/master/img/mavenpath.png)
```
* 在cmd下执行 mvn -v 查看maven版本
C:\Users\wyx>mvn -v
Apache Maven 3.3.9 (bb52d8502b132ec0a5a3f4c09453c07478323dc5; 2015-11-11T00:41:47+08:00)
Maven home: C:\kfrj\apache-maven-3.3.9\bin\..
Java version: 1.8.0_181, vendor: Oracle Corporation
Java home: C:\Java\jdk1.8.0_181\jre
Default locale: zh_CN, platform encoding: GBK
OS name: "windows 10", version: "10.0", arch: "amd64", family: "dos"
```
### Eclipse中配置maven
Eclipse中内置了maven,把Eclipse中内置默认的maven修改为我们本地安装的maven  
![eclipsemaven](https://raw.githubusercontent.com/duiying/note/master/img/eclipsemaven.png)  
修改User settings,把Eclipse中内置默认的User settings修改为我们本地安装的User settings  
![usersetting](https://raw.githubusercontent.com/duiying/note/master/img/usersetting.png)  
settings.xml文件路径修改完成之后,点击Update Settings和Reindex
### renren-fast后端部署
参考官方文档 https://www.renren.io/guide
```
* 下载
    git clone https://gitee.com/renrenio/renren-fast.git
* 导入
    右键->Import->Existing Maven Projects
创建数据库 renren_fast ,数据库编码为 UTF-8
执行 db/mysql.sql 文件,初始化数据
修改 application-dev.yml ,更新数据源1和数据源2的MySQL账号和密码
运行 io.renren.RenrenApplication.java 的 main 方法，则可启动项目
```
访问Swagger路径：http://localhost:8080/renren-fast/swagger/index.html