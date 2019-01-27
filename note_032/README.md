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
填写描述信息  
![jenkins-first-freestyle](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-first-freestyle.png)  
添加参数  
![jenkins-param-build](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-param-build.png)  
回到jenkins首页, 点击 test-freestyle-job configure  
![jenkins-freestyle-configure](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-freestyle-configure.png)   
查看gitlab repo地址 
![gitlab-repo-url](https://raw.githubusercontent.com/duiying/note/master/img/gitlab-repo-url.png)   
在gitlab ssh keys 添加jenkins主机的秘钥
```
[root@localhost ~]# cat /root/.ssh/id_rsa.pub 
```
![gitlab-add-key](https://raw.githubusercontent.com/duiying/note/master/img/gitlab-add-key.png)   
添加仓库地址, 选择root  
![jenkins-code-management](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-code-management.png)  
build配置 执行shell
![jenkins-build-shell](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-build-shell.png)  
```
#!/bin/sh

export PATH="/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin"

# Print env variable
echo "[INFO] Print env variable"
echo "Current deployment envrionment is $deploy_env" >> test.properties
echo "THe build is $version" >> test.properties
echo "[INFO] Done..."

# Check test properties
echo "[INFO] Check test properties"
if [ -s test.properties ]
then
  cat test.properties
  echo "[INFO] Done..."
else
  echo "test.properties is empty"
fi

echo "[INFO] Build finished..."
```
点击save, 然后Build with Parameters  
![jenkins-build-param](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-build-param.png)  
Build  
![jenkins-build](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-build.png)  
