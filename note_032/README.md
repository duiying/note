# Jenkins Job构建
> Jenkins Job 介绍  
```
代表一个任务或者项目
可配置与可执行
执行后的记录称之为build
日志监控与记录
所有文件集中保存
```
> Freestyle Job 和 Pipeline Job的区别  

**Freestyle Job** 
```
需要在页面添加模块配置项与参数完成配置
每个Job仅能实现一个开发功能
无法将配置代码化, 不利于Job配置迁移与版本控制
逻辑相对简单, 无需额外学习成本
```

**Pipeline Job**  
```
所有模块, 参数配置都可以体现为一个pipeline脚本
可以定义为多个stage构建一个管道工作集
所有配置代码化, 方便Job配置迁移与版本控制
需要pipeline脚本语法基础
```
### 准备工作  

```
# ssh连接Jenkins主机
ssh root@192.168.2.155
# 配置Jenkins主机的hosts
[root@localhost ~]# vi /etc/hosts
192.168.2.154 gitlab.example.com
# 安装git curl
[root@localhost ~]# yum -y install git curl
# 关闭git SSL安全认证
[root@localhost ~]# git config --system http.sslVerify false
# 查看命令是否执行成功
[root@localhost ~]# echo $?
0
```
> Jenkiins 配置 Git Plugin 
 
![jenkins-git-plugin](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-git-plugin.png)  
在Git Plugin选项中填写以下信息, 点击Save  
![jenkins-git-plugin-setting](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-git-plugin-setting.png)  
> Jenkins 添加 Git Credential凭据  

![jenkins-unrestricted](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-unrestricted.png)  

### 添加一个freestyle Job
![jenkins-new-job](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-new-job.png)  
![jenkins-new-job-name](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-new-job-name.png)  


```

```


