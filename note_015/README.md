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
Eclipse中内置了maven,我们修改Eclipse中内置默认的maven为我们本地安装的maven  
![eclipsemaven](https://raw.githubusercontent.com/duiying/note/master/img/eclipsemaven.png)
