# redis
### redis介绍
```
redis是一个C语言编写的,高性能的,基于键值对的非关系型数据库

redis支持的键值的数据类型有五种
    字符串类型String
    散列类型Hash
    列表类型List
    集合类型Set
    有序集合类型Sorted Set
    
redis的优势
    性能极高
    支持数据持久化
    丰富的数据类型
```
### redis安装
```
* 首先安装必要的工具以及依赖
    yum -y install gcc gcc-c++ libxml2 libxml2-devel git autoconf telnet pcre-devel curl-devel
* 源码包统一放在/usr/src/目录
* 进入目录
    cd /usr/src/
* 下载
    wget http://download.redis.io/releases/redis-4.0.2.tar.gz
* 解压文件
    tar -xzvf redis-4.0.2.tar.gz
* 进入目录
    cd redis-4.0.2/
* 编译
    make
* 安装
    make install
* 检查是否安装成功
    * 进入目录
        /usr/src/redis-4.0.2/src
    * 启动redis服务端
        ./redis-server
    * 客户端登录
    	[root@VM_12_22_centos ~]# redis-cli -h 127.0.0.1 -p 6379
    * 以指定配置文件启动redis服务端
    	cd /usr/src/redis-4.0.2
        cp redis.conf myredis.conf
        * 编辑myredis.conf,修改daemonize no为yes,表示让redis服务端在后台启动;为了安全性,也可以将port 6379改为别的端口,这里暂时不做修改
		[root@VM_12_22_centos redis-4.0.2]# cd /usr/src/redis-4.0.2/src/
		[root@VM_12_22_centos src]# ./redis-server /usr/src/redis-4.0.2/myredis.conf
        * 查看是否启动redis服务
            ps -ef | grep redis
```