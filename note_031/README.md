# Jenkins的介绍与安装
**Jenkins介绍**
```
Jenkins是一个开源持续集成工具
开发工具: Java
功能: 提供了软件开发的持续集成服务
特点: 支持主流软件配置管理, 配合实现软件配置管理, 持续集成服务
```

**Jenkins的优势和应用场景**
```
主流的运维开发平台, 兼容所有主流开发环境
插件市场可与海量业内主流开发工具实现集成
Job为配置单位与日志管理, 使运维与开发人员能够协同工作
权限管理划分不同Job不同角色
强大的负载均衡功能, 保证项目的可靠性
```

**Jenkins的安装与配置**  
[Vmware安装Centos7.5初始化操作](https://github.com/duiying/note/blob/master/note_018)  
**安装步骤**
```
# 安装Java(需要1.8及以上)
[root@localhost ~]# yum install -y java
# 查看Java版本
[root@localhost ~]# java -version
openjdk version "1.8.0_191"
OpenJDK Runtime Environment (build 1.8.0_191-b12)
OpenJDK 64-Bit Server VM (build 25.191-b12, mixed mode)

# 安装Jenkins
[root@localhost ~]# wget -O /etc/yum.repos.d/jenkins.repo https://pkg.jenkins.io/redhat-stable/jenkins.repo

[root@localhost ~]# rpm --import https://pkg.jenkins.io/redhat-stable/jenkins.io.key

[root@localhost ~]# yum install -y jenkins

# 创建Jenkins服务用户
[root@localhost ~]# useradd deploy
# 更改Jenkins启动用户和查看端口
[root@localhost ~]# vim /etc/sysconfig/jenkins
29行左右 JENKINS_USER="jenkins" 改为 JENKINS_USER="deploy"
56行左右查看Jenkins端口 JENKINS_PORT="8080"
[root@localhost ~]# cp /etc/sysconfig/jenkins{,.bak}
# 更改目录权限
[root@localhost ~]# chown -R deploy:deploy /var/lib/jenkins
[root@localhost ~]# chown -R deploy:deploy /var/log/jenkins
# 启动Jenkins
[root@localhost ~]# systemctl start jenkins
# 查看8080端口, 发现没有被占用, 说明Jenkins启动失败
[root@localhost ~]# lsof -i:8080
# 查看Jenkins日志
[root@localhost ~]# cat /var/log/jenkins/jenkins.log
java.io.FileNotFoundException: /var/cache/jenkins/war/META-INF/MANIFEST.MF (Permission denied)
# 给deploy赋予目录权限
[root@localhost ~]# chown -R deploy:deploy /var/cache/jenkins/
# 重启Jenkins
[root@localhost ~]# systemctl start jenkins
[root@localhost ~]# lsof -i:8080
COMMAND  PID   USER   FD   TYPE DEVICE SIZE/OFF NODE NAME
java    2177 deploy  162u  IPv6  27305      0t0  TCP *:webcache (LISTEN)

# 至此, Jenkins安装并启动成功
```

**更改hosts**  
```
sudo vi /etc/hosts
192.168.2.143 jenkins.example.com
```
**浏览器访问**  
```
http://jenkins.example.com:8080
```

**初始化界面**  
![jenkins-init](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-init.png)  
**开始界面**  
![jenkins-start](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-start.png)  
**正在安装插件界面**  
![jenkins-starting](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-starting.png)  
**创建第一个admin用户界面**  
![jenkins-admin](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-admin.png)  
**URL配置界面**  
![jenkins-url](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-url.png)  
**ready界面**  
![jenkins-ready](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-ready.png)  
**首页界面**  
![jenkins-index](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-index.png)  
