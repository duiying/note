# docker基础操作

### Centos下安装docker的环境要求
```
如果是Centos7, 要求系统为64位/系统内核版本为 3.10 以上
如果是Centos6.5或更高版本, 要求系统为64位/系统内核版本为 2.6.32-431 或者更高版本
```

### 我的系统版本
```
[root@localhost ~]# cat /etc/redhat-release 
CentOS Linux release 7.5.1804 (Core)
[root@localhost ~]# uname -r
3.10.0-862.el7.x86_64
```


### Centos下安装docker
```
* 升级所有包
    [root@localhost ~]# yum -y upgrade
* 安装docker
    [root@localhost ~]# yum -y install docker
* 查看所安装的docker版本
    [root@localhost ~]# docker -v
    Docker version 1.13.1, build 8633870/1.13.1
* docker的启动/关闭/重启
    service docker start/stop/restart
    或者
    systemctl start/stop/restart docker.service
```

### 解决docker安装后无法启动
```
* 当使用yum方式安装完docker之后, 使用 systemctl start docker.service 命令发现无法启动docker
* 提示查看启动状态
    systemctl status docker.service -l
* 发现问题是selinux和docker版本不匹配, 于是关闭selinux, 然后重启服务器
    * 编辑配置文件
        vim /etc/sysconfig/selinux
    * 将 SELINUX=enforcing 改为 SELINUX=disabled
    * 重启服务器
        reboot
```


### 配置阿里云docker镜像加速器
```
* 进入 https://dev.aliyun.com/ , 登录, 进入 https://cr.console.aliyun.com/#/accelerator 获取镜像加速器信息
* 安装／升级Docker客户端, 推荐安装1.10.0以上版本的Docker客户端
* 配置镜像加速器(通过修改daemon配置文件/etc/docker/daemon.json来使用加速器)
    mkdir -p /etc/docker
    
    tee /etc/docker/daemon.json <<-'EOF'
    {
      "registry-mirrors": ["https://8auvmfwy.mirror.aliyuncs.com"]
    }
    EOF
    
    systemctl daemon-reload
    
    systemctl restart docker
```

### docker命令
镜像仓库
```
* docker pull --从镜像仓库中拉取指定镜像 
    # 从镜像仓库中拉取nginx镜像
    docker pull nginx 
```

本地镜像管理
```
* docker images --列出本地镜像
    docker images
    
* docker rmi <image id> --通过镜像ID来删除指定镜像
    # 删除PHP镜像
    docker rmi 8473cbe51b22
* docker rmi <image name> --通过镜像名称来删除指定镜像
    # 删除PHP镜像(image name最好写全)
    docker rmi docker.io/php
```

容器生命周期管理
```
* docker run --创建并运行一个新容器
    # 创建并运行一个新nginx容器
    docker run --name N1 -p 80:80 -d nginx
    # 参数说明
    # --name N1 为容器指定一个名称为N1
    # -p 80:80 端口映射, 格式为 宿主端口:容器端口
    # -d 后台运行容器, 并返回容器ID
    # nginx 使用的是docker镜像nginx
    
    # 创建并运行一个新的nginx容器, 并将宿主机/wyx目录映射到容器/wyx目录
    # 首先在宿主机新建wyx目录 
    [root@10-9-50-240 ~]# mkdir /wyx
    # 在宿主机wyx目录新建文件
	[root@10-9-50-240 ~]# echo hello > /wyx/1.txt
    # 创建N2容器, 容器中会新建/wyx目录
    docker run --name N2 -p 81:80 -d -v /wyx:/wyx nginx
    # 参数说明
    # -v表示新建数据卷, 数据卷:将宿主机目录映射到容器目录, 数据卷可以实现数据共享和数据持久化
    # 如果容器删除, 文件不会丢失
    
* docker exec --在运行中的容器中执行命令
    # 在运行的N1容器中执行bash命令, 往nginx欢迎页面追加 'hello wyx' 字符串
    [root@10-9-50-240 ~]# docker exec -it N1 bash
    root@568b8065ff60:/# echo hello wyx >> /usr/share/nginx/html/index.htm
    # 参数说明
    # -i 以交互模式运行容器, 通常与-i同时使用
    # -t 为容器重新分配一个伪输入终端, 通常与-i同时使用
    # N1 容器名称
    # bash 在容器中执行bash

* docker create --创建一个新的容器但不启动它
    # 创建一个新的nginx容器, 并为容器指定一个名称为N2
    docker create --name N2 nginx 

* docker start/stop/restart --启动/停止/重启容器
    # 启动N2容器
    docker start N2
    
* docker rm --删除容器
    # 删除容器, 容器需要先stop
    docker rm N2
    # 强制删除正在运行的容器
    docker rm -f N2
    # 强制删除所有容器, 包括正在运行的和停止运行的
    docker rm -f $(docker ps -aq)
    
* docker pause/unpause --暂停/恢复容器中所有进程
    # 暂停N2容器中所有进程
    docker pause N2
    # 恢复N2容器中所有进程
    docker unpause N2
```
容器操作
```
* docker ps --列出容器
    # 查看正在运行的容器信息
    docker ps 
    # 查看所有的容器信息, 包含正在运行的和未运行的
    docker ps -a
    # 查看所有的容器信息, 包含正在运行的和未运行的, 但是只显示容器编号
    docker ps -aq
    
* exit --退出容器的交互


```