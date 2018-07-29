# redis
### 内容概要
* redis介绍
* redis安装
* 键名操作
* String类型操作
* Hash类型操作
* List类型操作
* Set类型操作
* Sorted Set类型操作
* 安装PHP以及PHP安装redis扩展
* PHP操作String类型
* PHP操作Hash类型
* PHP操作List类型
* PHP操作Set类型
* PHP操作Sorted Set类型
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
![Hash](https://raw.githubusercontent.com/duiying/note/master/img/hash.png)  

命令|含义
:---|:---
hset key field value|给字段赋值,如果不存在则先创建,如果存在则更新
hget key field|获取hash中指定字段的值
hmset key field1 value1 ... fieldN valueN|给hash多个字段赋值
hmget key field1 ... fieldN|获取hash中多个指定字段的值
hincrby key field integer|给hash中指定字段加上指定值
hexists key field|hash中指定字段是否存在
hdel key field|删除hash中指定字段
hlen key|获取hash中字段数量
hkeys key|获取hash中所有的字段
hvals key|获取hash中所有的值
hgetall key|获取hash中所有的字段和值
### List类型操作
列表类型可以存储一个有序的字符串列表,内部使用双向链表实现,列表里面的元素可以重复  
应用场景: 最新消息排行,消息队列    
![List](https://raw.githubusercontent.com/duiying/note/master/img/list.png)  

命令|含义
:---|:---
lpush key value [value ...]|在list的头部(左边)添加元素
rpush key value [value ...]|在list的尾部(右边)添加元素
llen key|返回list的长度,key不存在返回0,key对应的值不是list类型则返回错误
lrange key start end|返回指定区间内的元素,下标从0开始
ltrim key start end|截取list,保留指定区间内元素
lset key index value|设置list中指定下标的元素值
lrem key count value|从key对应list中删除count个和value相同的元素.count为0时删除全部
lpop key|从list头部删除元素,并返回删除元素
rpop key|从list尾部删除元素,并返回删除元素
lindex key index|获取list中指定索引的值
### Set类型操作
集合类型里面的元素无序且不分左右(头尾),并且元素不可重复  
应用场景: qq好友推荐,微博系统的关注关系  
![Set](https://raw.githubusercontent.com/duiying/note/master/img/set.png)  

命令|含义
:---|:---
sadd key member [member ...]|添加一个string元素到set集合中,成功返回1,元素已经存在返回0
srem key member [member ...]|从set中移出给定元素,成功返回1
smove p1 p2 member|从p1对应set中移出member并添加到p2对应set中
scard key|返回set中元素的个数
sismember key member|判断member是否在set中
sinter key1 key2 [key ...]|返回指定list的交集
sinterstore p1 key1 key2 [key ...]|同sinter,但是会同时把交集存在p1中
sunion key1 key2 [key ...]|返回指定list的并集
sunionstore p1 key1 key2 [key ...]|同sunion,并同时把并集保存到p1中
sdiff key1 key2 [key ...]|返回指定list的差集
sdiffstore dstkey key1 key2 [key ...]|同sdiff,并同时把差集保存到dstkey中
smembers key|返回set的所有元素,结果是无序的
### Sorted Set类型操作
有序集合类型,集合中每个元素都关联了一个分数,通过这个分数来对元素进行从小到大的排序,有序集合中元素不可重复但是分数可以重复  
如果有两个元素的score相同的话，则按照value来排序  
应用场景: 数据排序  
![Set](https://raw.githubusercontent.com/duiying/note/master/img/sortedset.png)

命令|含义
:---|:---
zadd key score member|添加元素到集合,元素在集合中存在则更新对应score
zrem key member|删除指定元素,1表示成功,如果元素不存在则返回0
zincrby key incr member|按照incr幅度增加对应member的score值,返回score值
zrank key member|返回指定元素在集合中的排名,集合元素是按score从小到大排序的
zrevrank key member|同上, 但是集合中元素是按score逆序的
zrange key start end|从集合中取出指定区间元素,返回有序结果
zrevrange key start end|同上,返回结果是按socre逆序
zrangebyscore key min max|返回集合中score在给定区间的元素
zcount key min max|返回集合中score在给定区间的数量
zcard key|返回集合中元素个数
zscore key element|返回给定元素对应的score
zremrangebyrank key min max|删除集合中排名在给定区间的元素
zremrangebyscore key min max|删除集合中score在给定区间的元素
### 安装PHP以及PHP安装redis扩展
```
* 安装PHP7
    * 下载地址
        http://php.net/get/php-7.2.2.tar.gz/from/a/mirror
    * 进入目录  
        cd /usr/src/
    * 解压文件
        tar -xzvf php-7.2.2.tar.gz
    * 进入目录
        cd php-7.2.2/
    * 配置
        ./configure --prefix=/home/work/study/soft/php --with-curl
    * 编译
        make
    * 安装
        make install
    * 将PHP命令加入到环境变量
        vi ~/.bash_profile
        * 在最后增加一行
            alias php=/home/work/study/soft/php/bin/php
        * 使配置文件生效
            source ~/.bash_profile
        * 使用PHP命令检查PHP版本
            [root@VM_12_22_centos /]# php -v
            PHP 7.2.2 (cli) (built: Jul 19 2018 10:58:51) ( NTS )
            Copyright (c) 1997-2018 The PHP Group
            Zend Engine v3.2.0, Copyright (c) 1998-2018 Zend Technologies
        * 将源码包下的php.ini拷贝到PHP安装目录,并且重命名
            [root@VM_12_22_centos swoole]# cd /usr/src/php-7.2.2/
            [root@VM_12_22_centos php-7.2.2]# cp php.ini-development /home/work/study/soft/php/etc/
            [root@VM_12_22_centos php-7.2.2]# cd /home/work/study/soft/php/etc/
            [root@VM_12_22_centos etc]# mv php.ini-development php.ini
        * 查看php.ini文件的生效目录并移动php.ini到该目录
            [root@VM_12_22_centos etc]# php -i | grep php.ini
            Configuration File (php.ini) Path => /home/work/study/soft/php/lib
            [root@VM_12_22_centos etc]# mv /home/work/study/soft/php/etc/php.ini /home/work/study/soft/php/lib/
* PHP安装redis扩展
    * 进入目录
        cd /usr/src/
    * 下载
        git clone https://github.com/phpredis/phpredis
    * 进入目录
        cd phpredis/
    * 生成configure文件
        /home/work/study/soft/php/bin/phpize
    * 配置
        ./configure --with-php-config=/home/work/study/soft/php/bin/php-config
    * 编译
        make
    * 安装
        make install
    * 修改php.ini,新增一行extension=redis(也有可能是extension=redis.so)
        vim /home/work/study/soft/php/lib/php.ini
    * 使用php -m命令查看PHP的扩展发现有redis,说明redis扩展安装成功
```
### PHP操作String类型
```
<?php
/**
 * String 类型操作
 */

// 连接redis
$redis = new \Redis();
$redis->connect('127.0.0.1', 6379);

// 设置值
$redis->set('string1', 'val1');

// 获取值
$val = $redis->get('string1');
var_dump($val);

// 设置值为整数
$redis->set('string2', 4);

// 进行加减操作
$redis->incr('string2', 2);

// 获取值
$val = $redis->get('string2');
var_dump($val);

// 删除key
$redis->delete('string1');
$val = $redis->get('string1');
var_dump($val);
```
运行:
```
string(4) "val1"
string(1) "6"
bool(false)
```
### PHP操作Hash类型
```
<?php
/**
 * Hash 类型操作
 */

// 连接redis
$redis = new \Redis();
$redis->connect('127.0.0.1', 6379);

// 给字段赋值
$redis->hSet('hash1', 'name', 'wyx');
$redis->hSet('hash1', 'age', '18');
$redis->hSet('hash1', 'gender', '1');

// 获取字段值
$name = $redis->hGet('hash1', 'name');
var_dump($name);

// 获取多个字段值
$val = $redis->hMGet('hash1', array('name', 'age', 'gender'));
var_dump($val);
```
运行:
```
string(3) "wyx"
array(3) {
  ["name"]=>
  string(3) "wyx"
  ["age"]=>
  string(2) "18"
  ["gender"]=>
  string(1) "1"
}
```
### PHP操作List类型
```
<?php
/**
 * List 类型操作
 */

// 连接redis
$redis = new \Redis();
$redis->connect('127.0.0.1', 6379);

// 在list的头部加入元素
$redis->lPush('list1', 'val1');
$redis->lPush('list1', 'val2');
$redis->lPush('list1', 'val3');

// 从list的尾部删除元素,并返回删除元素
$val1 = $redis->rPop('list1');
var_dump($val1);

// 从list的尾部删除元素,并返回删除元素
$val2 = $redis->rPop('list1');
var_dump($val2);
```
运行:
```
string(4) "val1"
string(4) "val2"
```
### PHP操作Set类型
```
<?php
/**
 * Set 类型操作
 */

// 连接redis
$redis = new \Redis();
$redis->connect('127.0.0.1', 6379);

// 向set集合中添加元素
$redis->sAdd('set1', 'val1');
$redis->sAdd('set1', 'val2');
$redis->sAdd('set1', 'val3');
$redis->sAdd('set1', 'val3');

// 获取set集合中的元素数量
$number = $redis->sCard('set1');
var_dump($number);

// 获取set集合中的所有元素
$val = $redis->sMembers('set1');
var_dump($val);
```
运行:
```
int(3)
array(3) {
  [0]=>
  string(4) "val3"
  [1]=>
  string(4) "val1"
  [2]=>
  string(4) "val2"
}
```
### PHP操作Sorted Set类型
```
<?php
/**
 * Sorted Set 类型操作
 */

// 连接redis
$redis = new \Redis();
$redis->connect('127.0.0.1', 6379);

// 向集合中添加元素
$redis->zAdd('zset1', 100, 'user1');
$redis->zAdd('zset1', 90, 'user2');
$redis->zAdd('zset1', 930, 'user3');

// 从低到高输出集合的排行
$res1 = $redis->zRange('zset1', 0, -1);
var_dump($res1);

// 从高到低输出集合的排行
$res2 = $redis->zRevRange('zset1', 0, -1);
var_dump($res2);
<?php
/**
 * Sorted Set 类型操作
 */

// 连接redis
$redis = new \Redis();
$redis->connect('127.0.0.1', 6379);

// 向集合中添加元素
$redis->zAdd('zset1', 100, 'user1');
$redis->zAdd('zset1', 90, 'user2');
$redis->zAdd('zset1', 930, 'user3');

// 从低到高输出集合的排行
$res1 = $redis->zRange('zset1', 0, -1);
var_dump($res1);

// 从高到低输出集合的排行
$res2 = $redis->zRevRange('zset1', 0, -1);
var_dump($res2);
```
运行:
```
array(3) {
  [0]=>
  string(5) "user2"
  [1]=>
  string(5) "user1"
  [2]=>
  string(5) "user3"
}
array(3) {
  [0]=>
  string(5) "user3"
  [1]=>
  string(5) "user1"
  [2]=>
  string(5) "user2"
}
```
