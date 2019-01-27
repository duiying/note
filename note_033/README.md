# Jenkins pipeline编写规范
> 一段pipeline脚本示例  

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
> pipeline基础架构  

```
所有代码包裹在pipeline{}层内
stages{}层用来包含该pipeline所有stage子层
stage{}层用来包含我们具体要编写任务的steps{}子层
steps{}层用来添加我们具体需要调用的模块语句
```

> agent区域  

```
agent定义pipeline在哪里运行(可以使用any none 或 具体的jenkins node主机名等)
例如: 我们要特指在node1上执行, 可以写成 agent {node {label 'node1'}}
```

> environment区域  

```
用来定义我们的环境变量, 格式: 变量名称=变量值
可以定义全局环境变量, 应用于所有stage任务
也可以定义stage环境变量, 应用于单独的stage任务
例如: 

pipeline {
    agent {node {label 'master'}}

    environment {
        PATH="/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin"
    }
    
    stages {
        stage("build") {
           environment {
                JAVA_HOME="/usr/lib/jre"
           }
           steps {
                ...
           } 
        }
    }
}
```
> 常用steps区域  

```
echo 打印输出
sh 调用shell
git 调用git命令
```

> script区域  

```
在steps{}内定义script
groovy脚本语言
用来进行脚本逻辑运算
例如:

#!groovy
pipeline {
    agent {node {label 'master'}}

    environment {
        user="deploy"
    }
    
    stages {
        stage("build") {
           steps {
                echo "hello"
                script{
                    def servers = ["node1","node2"]
                    for (int i=0; i<servers.size(); ++i) {
                        echo "testing ${servers[i]} server"
                    }
                }
           } 
        }
    }
}
```