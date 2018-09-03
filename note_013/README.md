# 面试
通过面试题对一些看似简单的知识点进行尽可能的挖掘,弥补自己的不足
## 分类
* Redis相关
* Git相关
* PHP基础相关
* PHP函数相关
* 面向对象相关
* HTTP相关
* Linux相关
* MySQL相关

### Redis相关
Redis支持的数据类型有哪些
***
```
redis支持的键值的数据类型有五种
    字符串类型String
    散列类型Hash
    列表类型List
    集合类型Set
    有序集合类型Sorted Set
```
### Git相关
Git分支管理策略
***
```

```
### PHP基础相关
PHP重写是什么?重载是什么?
***
```
方法的重写(override): 又叫方法的重载
方法的重写指的是子类重写父类中的方法
要实现重写,需要满足:
    子类中重写的方法 方法名称以及该方法的参数个数和父类中被重写的方法一样,但是并不要求参数的名称相同
    子类中重写的方法 访问权限要>=父类中的被重写方法的访问权限
```
```
Example: override.php
```
![override](https://raw.githubusercontent.com/duiying/note/master/img/override.png)
```
方法的重载(overload): 存在多个同名方法,方法的参数个数 or 参数类型不同,当传入不同的参数时,调用不同的方法
PHP不像java,在PHP中不允许多个同名方法,但是可以用__call魔术方法来实现重载
```
```
Example: overload.php
```
![overload](https://raw.githubusercontent.com/duiying/note/master/img/overload.png)

说一下值传递和引用传递的区别,数组和对象是值传递还是引用传递
***
```
值传递: 函数范围内对值的改变在函数外部不会起到作用
引用传递: 函数范围内对值的改变在函数外部也会起到作用
值传递时PHP会复制值,消耗资源;引用传递时传递的是地址,资源消耗小;

数组默认是值传递,可以用&符号做到引用传递
对象是引用传递
NULL是值传递,可以用&符号做到引用传递
```
```
Example: transmit.php
```
![transmit](https://raw.githubusercontent.com/duiying/note/master/img/transmit.png)
```
深入理解对象的传递方式
```
```
Example: objectTransmit.php
```
![objectTransmit](https://raw.githubusercontent.com/duiying/note/master/img/objectTransmit.png)

### PHP函数相关
用array_merge()函数合并数组的时候,遇到索引相同的情况会怎么处理?还有哪些合并数组的方式,它们之间有什么不同?
***
```
对于array_merge()函数
如果是相同的数字索引,array_merge()会重建索引,新的索引从0开始
如果是相同的字符串索引,array_merge()会用后面数组的值覆盖掉前面的值
其他合并数组的方式有: + 和 array_merge_recursive()函数
```
```
Example: array_merge.php
```
![array_merge](https://raw.githubusercontent.com/duiying/note/master/img/array_merge.png)
```
执行结果
```
![array_merge_res](https://raw.githubusercontent.com/duiying/note/master/img/array_merge_res.png)
```
对于用+来合并数组
对于相同的数字索引和字符串索引,+会前面数组的值覆盖掉后面数组的值,合并后的数组索引保持不变
```
```
Example: add.php
```
![add](https://raw.githubusercontent.com/duiying/note/master/img/add.png)
```
执行结果
```
![add_res](https://raw.githubusercontent.com/duiying/note/master/img/add_res.png)
```
对于用array_merge_recursive()函数合并数组
如果是相同的数字索引,处理方式和array_merge相同,都是重建索引,新的索引从0开始
如果是相同的字符串索引,会把相同的索引放到一个数组里面
```
```
Example: array_merge_recursive.php
```
![array_merge_recursive](https://raw.githubusercontent.com/duiying/note/master/img/array_merge_recursive.png)
```
执行结果
```
![array_merge_recursive_res](https://raw.githubusercontent.com/duiying/note/master/img/array_merge_recursive_res.png)
### 面向对象相关

### HTTP相关
cookie和session了解吗?有什么区别?session保存在服务器哪个目录?禁用cookie怎么保存会话信息
***
```
因为HTTP协议是无状态的,一个用户在访问不同页面时,要识别出这是同一个用户,就要用到会话技术,会话技术包括cookie和session
cookie保存在客户端,session保存在服务端,保存目录由php.ini里面session.save_path参数确定

session的原理
前提是开启了session,第一次访问页面时,服务端生成一个不重复的sessionid(当前会话id)以及命名为sess_xxx的session文件,保存在php.ini文件中指定的目录
文件中保存着存储的session信息,xxx即为sessionid,sessionid可以通过session_id()函数来获取
同时返回响应头(Response Header)Set-Cookie:PHPSESSID=xxxxxxx
客户端接受到Set-Cookie响应头,将sessionid写入cookie,cookie的key为PHPSESSID,value为sessionid
比如PHPSESSID=jlis2mcmv6d5hejkemom77ibm3

当第二次访问页面时,客户端会把cookie放在请求头(Request Header)中,服务端识别PHPSESSID这个cookie
然后根据这个cookie获取当前会话ID(sessionid),从而找到对应的session文件,再从session文件中读取信息

cookie和session的区别
cookie保存在客户端,session依赖于cookie保存在服务端,如果禁用cookie则session也不可用
cookie大小有限制,session大小限制取决于服务器
cookie信息保存在客户端,不安全,session保存在服务端,安全
session保存在服务端会消耗服务端资源,如果考虑到性能可以保存到cookie

禁用cookie
禁用cookie后,服务器每次session_start()的时候都会创建一个单独的session文件,后果就是无法让多个页面共享同一份session,也就是会话失效
解决方法:
首先判断是否有PHPSESSID参数是否存在,如果存在就使用这个参数来获得sessionid,如果没有就创建一个新的sessionid;
然后在每个链接上都加上PHPSESSID=sessionid
Example: x.php
<?php

if(isset($_GET['PHPSESSID'])){
    session_id($_GET['PHPSESSID']);
}

session_start();
$sid = session_id();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>页面</title>
</head>
<body>
<a href="xxx.php?PHPSESSID=<?php echo $sid;?>">链接</a>
</body>
</html>
```


### Linux相关
free命令
***
```
* Example
[root@localhost ~]# free 
              total        used        free      shared  buff/cache   available
Mem:         999936      346300      194540       21800      459096      421092
Swap:       2097148        1568     2095580
[root@localhost ~]# free -m
              total        used        free      shared  buff/cache   available
Mem:            976         338         190          21         448         411
Swap:          2047           1        2046
[root@localhost ~]# free -h
              total        used        free      shared  buff/cache   available
Mem:           976M        338M        189M         21M        448M        411M
Swap:          2.0G        1.5M        2.0G

* 命令说明
free命令是用来查看内存占用情况,-m表示以M为单位显示,-h表示以方便阅读的方式显示
total 内存总数
used 已经使用的内存数
free 空闲的内存数
shared 不必关心
buff/cache 缓存内存数
available 可用内存数
说明:buff/cache分为used和free两部分,free部分是可以回收的内存
total = used + free + buff/cache
available = free + buff/cache中可以回收的内存
```
top命令主要看什么参数
***
```
* Example
[root@localhost ~]# top
top - 11:12:18 up 4 days, 11:02,  3 users,  load average: 0.00, 0.01, 0.05
Tasks: 108 total,   2 running, 106 sleeping,   0 stopped,   0 zombie
%Cpu(s):  0.0 us,  0.3 sy,  0.0 ni, 99.7 id,  0.0 wa,  0.0 hi,  0.0 si,  0.0 st
KiB Mem :   999936 total,   194136 free,   346648 used,   459152 buff/cache
KiB Swap:  2097148 total,  2095580 free,     1568 used.   420808 avail Mem 

   PID USER      PR  NI    VIRT    RES    SHR S %CPU %MEM     TIME+ COMMAND                                          
   903 root      20   0  553164  14764   4128 S  0.3  1.5   1:39.11 tuned                                            
     1 root      20   0  193628   6448   3684 S  0.0  0.6   0:33.31 systemd                                          
     2 root      20   0       0      0      0 S  0.0  0.0   0:00.09 kthreadd 

* 命令说明
第一行
11:12:18 当前时间 
4 days, 11:02 系统运行时间
3 users 当前登录用户数
load average: 0.00, 0.01, 0.05 系统负载(1分钟 10分钟 15分钟),即任务队列的平均长度(单核CPU中不超过1是正常的,超过1说明负载压力大)
第二行
108 total 进程总数 2 running 运行进程数 106 sleeping 休眠进程数 0 stopped 终止进程数 0 zombie 僵尸进程数
第三行
0.0 us 用户空间占用cpu百分比
0.3 sy 内核空间占用cpu百分比
0.0 ni 用户进程空间内改变过优先级的进程占用cpu百分比
99.7 id 空闲cpu百分比
0.0 wa 等待输入输出（I/O）的cpu百分比
0.0 hi cpu处理硬件中断的时间
0.0 si cpu处理软件中断的时间 
0.0 st 用于有虚拟cpu的情况,用来指示被虚拟机偷掉的cpu时间
第四行
999936 total 内存总数
194136 free 空闲的内存数
346648 used 已经使用的内存数   
459152 buff/cache 缓存的内存数
第五行
2097148 total 总的交换空间
2095580 free 空闲的交换空间     
1568 used 已经使用的交换空间   
420808 avail Mem 可用交换空间

进程信息区统计信息区域的下方显示了各个进程的详细信息
PID 进程ID
USER 进程所有者用户名
PR 优先级
NI nice值,负值表示高优先级,正值表示低优先级   
VIRT 进程使用的虚拟内存总量   
RES 物理内存用量  
SHR 共享内存用量
S 该进程的状态;其中S代表休眠状态,D代表不可中断的休眠状态,R代表运行状态,Z代表僵尸状态,T代表停止或跟踪状态 
%CPU 进程占用的CPU时间和总时间的百分比 
%MEM 进程占用的物理内存和总内存的百分比    
TIME+ 累计CPU占用时间 
COMMAND 命令名/命令行
```
### MySQL相关
表的约束有哪些
***
```
为了防止数据表中插入错误的数据,在MySQL中,定义了一些维护数据库完整性的规则,即表的约束
* 常见约束
    PRIMARY KEY 主键约束
    FOREIGN KEY 外键约束
    NOT NULL 非空约束
    UNIQUE 唯一性约束(在MySQL中,唯一性约束和唯一索引概念不同,但是实际效果相同)
    DEFAULT 默认值约束
* Example
create table yueshu(id int primary key,
                    name varchar(20) NOT NULL UNIQUE,
                    location varchar(20) DEFAULT ''
);
* 说明
id字段有主键约束,name字段有唯一约束和非空约束,location字段有默认约束
```
MySQL存储引擎之MyIsam和Innodb的区别,如何选择
***
```
1)InnoDB支持事务;MyISAM不支持
2)MyISAM强调的是性能,查询速度比InnoDB快;InnoDB适合频繁修改以及涉及到安全性较高的应用
3)InnoDB支持外键;MyISAM不支持
4)MySQL5.5以后,默认存储引擎是InnoDB
5)InnoDB不支持全文索引;MyISAM支持全文索引
6)MyISAM仅支持表锁,并发较小;InnoDB支持行锁,并发较大
7)使用delete删除表的时候,InnoDB是逐行删除;而MyISAM是先DROP表,然后重新建表,MyISAM的效率快
8)对于select count(*) from 表名;MyISAM因为保存了表的行数可以直接取出;而InnoDB会遍历整个表来计算行数;但是对于加了WHERE条件,select count(*) from 表名 WHERE 条件;MyISAM和InnoDB都会遍历整个表来计算行号
```
MySQL中char和varchar类型的区别
***
```
char和varchar都用来表示字符串数据
varchar长度可变,比如varchar(10),如果存储'github',会占用6个字符的长度;
char长度不可变,比如char(10),如果存储''github',会占用10个字符的长度,'github'后面会跟4个空格,取数据的时候会将添加的空格trim掉
因为char的长度固定,方便数据的存储和查找,所以char的时间效率比varchar高,但是char的空间占用比varchar高

关于存储
MySQL4.0以前,char(20)和varchar(20)指的是20个字节长度,如果存放utf8汉字(3个字节)时,只能存放6个
5.0版本以上,char(20)和varchar(20)指的是20个字符长度,无论存放数字/字母/utf8汉字(3个字节),都可以存放20个

char和varchar的最大存储
char范围是0-255,可以存储255个字符
varchar是0-65535(可以存放65532个字节的数据)
为什么是65532呢,65535-1-2,减1的原因是实际存储从第二个字节开始,减2的原因是varchar头部的2个字节表示长度
实际应用中varchar长度限制的是一个行定义的长度,MySQL要求一个行的定义长度不能超过65535字节
若定义的表长度超过这个值，则提示
ERROR 1118 (42000): Row size too large. The maximum row size for the used table type, not counting BLOBs, is 65535. You have to change some columns to TEXT or BLOBs

MySQL对于varchar会有1-2个字节来保存字符长度,当字符数小于等于255时,MySQL只用1个字节来记录,因为2的8次方减1只能存到255,当字符数多于255就用2个字节

英文字符和数字占1个字节,GBK字符集每个汉字占两个字节,UTF8字符集每个汉字占三个字节
GBK 一个字符占1-2个字节,varchar最多能存 32766 个字符(65535-1-2 / 2)
UTF8 一个字符占1-3个字节,varchar最多能存 21844 个字符(65535-1-2 / 3)

举例说明
a)若一个表只有一个varchar类型,如定义为
create table t4(c varchar(N)) charset=gbk;
则此处N的最大值为(65535-1-2)/2 = 32766 个字符
减1的原因是实际存储从第二个字节开始,减2的原因是varchar头部的2个字节表示长度,除2的原因是字符编码是gbk

b)若一个表定义为
create table t4(c int, c2 char(30), c3 varchar(N)) charset=utf8;
则此处N的最大值为(65535-1-2-4-30*3)/3=21812
减1和减2与上例相同,减4的原因是int类型的c占4个字节,减30*3的原因是char(30)占用90个字节,编码是utf8 
```
tinyint占多少个字节,tinyint(1)能表示的数据范围
***
```
这个问题首先要掌握整数类型所占的字节数和能表示的数据范围,另外拓展一下,还要掌握浮点数类型和定点数类型
```
```
整型
```
数据类型|字节数|无符号取值范围|有符号取值范围
--|--|--|--|--
TINYINT|1|0-255|-128-127
SMALLINT|2|0-65535|-32768-32767
MEDIUMINT|3|0-16777215|-8388608-8388607
INT|4|0-4294967295|-2147483648-2147483647
BIGINT|8|0-18446744073709551615|-9223372036854775808-9223372036854775807
```
TINYINT(M)中M表示的含义是什么,比如TINYINT(1)
M的含义并不是允许存入的字符长度,无符号TINYINT(1)可以存入其最大值255,而且存入的数值无论是0还是255,占用的字节大小是固定的,都是1
那么这个M值到底代表什么,有什么作用?
整型的字节数已经限制了存入数值的取值范围,M值在这里不会有任何影响,比如TINYINT(1)和TINYINT(2)没有区别
如果设置zerofill(左前位置零填充),对于TINYINT(1)和TINYINT(2),如果存入1,TINYINT(1)存入的是1,而TINYINT存入的是01
也就是说,没有zerofill,M值是没用的
所以在设计字段的时候,mysql会自动分配长度:int(11)、tinyint(4)、smallint(6)、mediumint(9)、bigint(20)
就用这些默认长度就可以了,不用自己搞int(10),tinyint(1)之类的,基本没什么用而且导致表的字段类型多样化
TINYINT(M)、SMALLINT(M)、MEDIUMINT(M)、INT(M)、BIGINT(M)是同理的,没有zerofill,M值是没用的
```
```
浮点型和定点型
```
![float](https://raw.githubusercontent.com/duiying/note/master/img/float.png)
```
float数值类型用于表示单精度浮点数值,而double数值类型用于表示双精度浮点数值
float和double都是浮点型,而decimal是定点型
MySQL浮点型和定点型可以用类型名称后加(M,D)来表示,M表示该值的总共长度,D表示小数点后面的长度,M和D又称为精度和标度
如float(7,4),最大可存入-999.9999,如果尝试存入999.00009,则存入结果为999.0001

浮点型和定点型的区别是浮点型在一些情况下数据库中存放的是近似值,而定点型存放的永远都是精确值
浮点型不能做精确的计算,其计算结果不准确,只是近似,而定点型可以做精确的计算
```
```
* Example
mysql > create table float_test(id int, float_test float);
mysql > desc float_test;
+------------+---------+------+-----+---------+-------+
| Field      | Type    | Null | Key | Default | Extra |
+------------+---------+------+-----+---------+-------+
| id         | int(11) | YES  |     | NULL    |       |
| float_test | float   | YES  |     | NULL    |       |
+------------+---------+------+-----+---------+-------+
mysql > insert into float_test values(1, 123456),(2, 123.456),(3, 1234567),(4,1234.5678);
mysql > select * from float_test;
+------+------------+
| id   | float_test |
+------+------------+
|    1 |     123456 |
|    2 |    123.456 |
|    3 |     123570 |
|    4 |    1234.57 |
+------+------------+
从上面测试代码可知float默认保存6位精度(包括小数位和整数位),超过6位会被四舍五入并补入0
```
了解数据库三范式吗
***
```
1NF(第一范式)指的是表的属性(即列)具有原子性,不可再分
2NF(第二范式)指的是在第一范式的基础上,不能有部分依赖,部分依赖的前提是有组合主键,也就是非主键不能只依赖于部分主键
3NF(第三范式)指的是在第二范式的基础上,非主键字段和主键字段不能产生传递依赖
```
MySQL索引有哪些
***
```
普通索引(KEY/INDEX):基本索引
唯一索引(UNIQUE定义的索引):列值唯一,允许有空值
全文索引(FULLTEXT定义的索引):只可用于MyISAM存储引擎
主键索引(PRIMARY KEY):列值唯一,不允许有空值,一张表最多有一个主键索引
组合索引:在多个字段上创建的索引,只有查询条件使用了第一个字段时,该索引才会被使用
```
面向对象 抽象类和接口之间区别 设计模式了解哪些,单例模式的实现思路 抽象类能否定义非抽象方法 访问权限有哪些 面向对象的特性是什么,应用 魔术方法有哪些 用过哪些 mysql数组函数尽可能的多说  说一下常用的排序算法,,冒泡/插入/快排怎么实现,时间复杂度如何 trait了解吗 composer具体的命令用过哪些  函数重载 self和$this的区别
