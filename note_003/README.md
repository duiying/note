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
### 键名操作
命令|含义
:---|:---
keys pattern|返回匹配指定模式的所有key
keys *|获取redis中所有key
keys key1*|获取redis中以key1开头的key
exists key|指定key是否存在
del key1 key2 ... keyN|删除指定key
type key|返回指定key的value类型
randomkey|返回从当前数据库中随机的一个key
rename oldkey newkey|重命名
dbsize|返回当前数据库的key数量
expire key seconds|为指定key设置剩余秒数
ttl key|返回key的剩余秒数
select index|选择数据库(一共16个,0-15,默认是0)
move key index|把key从当前数据库移动到指定数据库
flushdb|删除当前数据库中所有key
flushall|删除当前所有数据库中的所有key
### String类型操作
命令|含义
:---|:---
set key value|设置key的值
get key|获取key的值
mset key1 value1 key2 value2 ... keyN valueN|一次设置多个key的值
mget key1 key2 ... keyN|一次获取多个key的值
incr key|对key的值做++操作
decr key|对key的值做--操作
incrby key integer|把key的值加上指定值
decrby key integer|把key的值减去指定值
append key value|把key的值追加一个字符串
substr key start end|截取key的值
### Hash类型操作
使用散列类型存储汽车对象的结构图  
![Hash](https://raw.githubusercontent.com/duiying/livecms/master/readmeimg/admin.png)  
命令|含义
:---|:---
hset key field value|给字段赋值,如果不存在则先创建,如果存在则更新
hget key field|获取指定的hash字段

