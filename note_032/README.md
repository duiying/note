# Jenkins Freestyle Job 和 Pipeline Job 的构建流程
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
查看Console Output输出信息  
![jenkins-console-output](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-console-output.png)  
![jenkins-freestyle-success](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-freestyle-success.png)  
至此, freestyle job构建成功
### 添加一个pipeline Job
回到首页, 点击 New 任务  
![jenkins-new-pipeline](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-new-pipeline.png)  
添加描述信息  
![jenkins-pipeline-desc](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-pipeline-desc.png)  
添加pipeline脚本  
![jenkins-pipeline-over](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-pipeline-over.png)  
```
#!groovy

pipeline {
    agent {node {label 'master'}}

    environment {
        PATH="/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin"
    }

    parameters {
        choice(
            choices: 'dev\nprod',
            description: 'choose deploy environment',
            name: 'deploy_env'
            )
        string (name: 'version', defaultValue: '1.0.0', description: 'build version')
    }

    stages {
        stage("Checkout test repo") {
            steps{
                sh 'git config --global http.sslVerify false'
                dir ("${env.WORKSPACE}") {
                    git branch: 'master', credentialsId:"897f7290-a1ce-49bb-948e-0a16cd8ab28a", url: 'https://gitlab.example.com/root/test.git'
                }
            }
        }
        stage("Print env variable") {
            steps {
                dir ("${env.WORKSPACE}") {
                    sh """
                    echo "[INFO] Print env variable"
                    echo "Current deployment environment is $deploy_env" >> test.properties
                    echo "The build is $version" >> test.properties
                    echo "[INFO] Done..."
                    """
                }
            }
        }
        stage("Check test properties") {
            steps{
                dir ("${env.WORKSPACE}") {
                    sh """
                    echo "[INFO] Check test properties"
                    if [ -s test.properties ]
                    then 
                        cat test.properties
                        echo "[INFO] Done..."
                    else
                        echo "test.properties is empty"
                    fi
                    """

                    echo "[INFO] Build finished..."
                }
            }
        }
    }
}
```
credentialsId:"xxx"  
![copy-credential](https://raw.githubusercontent.com/duiying/note/master/img/copy-credential.png)    
url: 'xxx' 改成自己的gitlab repo url  
然后点击save, 立即构建, 发现报错  
![jenkins-pipeline-error](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-pipeline-error.png)    
查看Console Output错误输出信息  
![jenkins-error-log](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-error-log.png)      
提示没有找到对应的参数, 所以点击Build With Parameters  
![jenkins-pipeline-param-build](https://raw.githubusercontent.com/duiying/note/master/img/jenkins-pipeline-param-build.png)   
查看Console Output输出信息  
```
Started by user admin
Running in Durability level: MAX_SURVIVABILITY
[Pipeline] node
Running on Jenkins in /var/lib/jenkins/workspace/test-pipeline-job
[Pipeline] {
[Pipeline] withEnv
[Pipeline] {
[Pipeline] stage (hide)
[Pipeline] { (Checkout test repo)
[Pipeline] sh
+ git config --global http.sslVerify false
[Pipeline] dir
Running in /var/lib/jenkins/workspace/test-pipeline-job
[Pipeline] {
[Pipeline] git
 > git rev-parse --is-inside-work-tree # timeout=10
Fetching changes from the remote Git repository
 > git config remote.origin.url https://gitlab.example.com/root/test.git # timeout=10
Fetching upstream changes from https://gitlab.example.com/root/test.git
 > git --version # timeout=10
using GIT_ASKPASS to set credentials 
 > git fetch --tags --progress https://gitlab.example.com/root/test.git +refs/heads/*:refs/remotes/origin/*
 > git rev-parse refs/remotes/origin/master^{commit} # timeout=10
 > git rev-parse refs/remotes/origin/origin/master^{commit} # timeout=10
Checking out Revision ded2f292daf54351a4cee588225f570be9dab481 (refs/remotes/origin/master)
 > git config core.sparsecheckout # timeout=10
 > git checkout -f ded2f292daf54351a4cee588225f570be9dab481
 > git branch -a -v --no-abbrev # timeout=10
 > git branch -D master # timeout=10
 > git checkout -b master ded2f292daf54351a4cee588225f570be9dab481
Commit message: "Merge branch 'feature-wangyaxian' into 'master'"
 > git rev-list --no-walk ded2f292daf54351a4cee588225f570be9dab481 # timeout=10
[Pipeline] }
[Pipeline] // dir
[Pipeline] }
[Pipeline] // stage
[Pipeline] stage
[Pipeline] { (Print env variable)
[Pipeline] dir
Running in /var/lib/jenkins/workspace/test-pipeline-job
[Pipeline] {
[Pipeline] sh
+ echo '[INFO] Print env variable'
[INFO] Print env variable
+ echo 'Current deployment environment is dev'
+ echo 'The build is 1.0.0'
+ echo '[INFO] Done...'
[INFO] Done...
[Pipeline] }
[Pipeline] // dir
[Pipeline] }
[Pipeline] // stage
[Pipeline] stage
[Pipeline] { (Check test properties)
[Pipeline] dir
Running in /var/lib/jenkins/workspace/test-pipeline-job
[Pipeline] {
[Pipeline] sh
+ echo '[INFO] Check test properties'
[INFO] Check test properties
+ '[' -s test.properties ']'
+ cat test.properties
Current deployment environment is dev
The build is 1.0.0
+ echo '[INFO] Done...'
[INFO] Done...
[Pipeline] echo
[INFO] Build finished...
[Pipeline] }
[Pipeline] // dir
[Pipeline] }
[Pipeline] // stage
[Pipeline] }
[Pipeline] // withEnv
[Pipeline] }
[Pipeline] // node
[Pipeline] End of Pipeline
Finished: SUCCESS
```
至此, pipeline job构建成功
