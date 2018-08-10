# 利用存储过程插入海量数据
### 环境
```
[root@VM_12_22_centos ~]# cat /etc/redhat-release
CentOS Linux release 7.3.1611 (Core)

mysql> select version();
+-----------+
| version() |
+-----------+
| 5.7.23    |
+-----------+
```
### 创建数据库和表
创建名称为testdb的数据库,然后执行如下sql:
```
DROP TABLE IF EXISTS `student`;
CREATE TABLE `student` (
  `student_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '学生ID',
  `student_name` varchar(50) NOT NULL DEFAULT '' COMMENT '学生姓名',
  `student_age` int(3) unsigned DEFAULT NULL COMMENT '学生年龄',
  PRIMARY KEY (`student_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='学生表';
```
可能遇到的问题: [Err] 1055 - Expression #1 of ORDER BY clause is not in GROUP BY clause ...  
解决方法是修改sql-mode,sql-mode是对sql语法支持的限制
```
* 编辑MySQL的配置文件
    vim /etc/my.cnf
* 在文件中添加一行内容
    sql_mode='NO_ENGINE_SUBSTITUTION'
* 重启MySQL服务
    service mysqld restart
```
### 定义函数
```
* 为了避免MySQL默认的语句结束符号';'和存储过程中SQL语句结束符号相冲突,可以使用DELIMITER改变存储过程的结束符,因为我是在Navicat下执行,所以这里我不需要改变结束符号
    mysql> delimiter $$
* 创建名称为rand_string的函数,该函数的作用是返回指定长度的随机字符串
    create function rand_string(len INT)
    # 指明返回值类型
    returns varchar(255)
    begin
            # MySQL中使用declare定义变量
            declare chars_str varchar(100) default 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            declare return_str varchar(255) default '';
            declare i int default 0;
            while i < len do
            # concat函数的作用是连接字符串
                    # MySQL中使用set为变量赋值
                    # substring(字符串,起始位置,长度)函数的作用是字符串截取,注意MySQL字符串下标是从1开始
                    set return_str =concat(return_str,substring(chars_str,floor(1+rand()*52),1));
                    set i = i + 1;
            end while;
        return return_str;
    end;
* 测试rand_string函数
    mysql> select rand_string(5);
    +----------------+
    | rand_string(5) |
    +----------------+
    | qhLjP          |
    +----------------+
* 创建名称为rand_num的函数,该函数的作用是返回指定范围的随机整数
    create function rand_num()
    # 指明返回值类型
    returns int(3)
    begin
        # MySQL中使用declare定义变量
        declare i int default 0;
        # i的范围为[10,30]
        # MySQL中使用set为变量赋值
        set i = floor(10+rand()*21);
        return i;
    end;   
* 测试rand_num函数
    mysql> select rand_num();
    +------------+
    | rand_num() |
    +------------+
    |         23 |
    +------------+
```
### 定义存储过程
```
# 创建存储过程 in表示输入参数 start是开始ID,max_num是插入条数
create procedure insert_student(in start int(10),in max_num int(10))
begin
    declare i int default 0;
    # 禁用事务自动提交
    set autocommit = 0;
    # repeat用于创建一个带有条件判断的循环过程,相当于do...while
    repeat
        #通过前面写的函数随机产生字符串和部门编号，然后加入到emp表
        insert into student values ((start+i), rand_string(6), rand_num());
        set i = i + 1;
        until i = max_num
    end repeat;
    # 整体提交事务可以提高效率
  commit;
end;
```
### 调用存储过程
```
# 调用存储过程,id从1号开始,插入8000000条记录
call insert_student(1,8000000);
```
### 检查调用存储过程的结果
```
mysql> select * from student limit 3;
+------------+--------------+-------------+
| student_id | student_name | student_age |
+------------+--------------+-------------+
|          1 | YNThbM       |          23 |
|          2 | YUDifd       |          30 |
|          3 | IuZPbL       |          21 |
+------------+--------------+-------------+
mysql> select * from student order by student_id desc limit 3;
+------------+--------------+-------------+
| student_id | student_name | student_age |
+------------+--------------+-------------+
|    8000000 | HdrCIK       |          21 |
|    7999999 | bkSLdg       |          18 |
|    7999998 | vFNYFe       |          23 |
+------------+--------------+-------------+
```
### MyISAM类型表文件介绍
.frm 表结构文件  
.MYD 表数据文件  
.MYI 表索引文件  
```
[root@VM_12_22_centos testdb]# pwd
/var/lib/mysql/testdb
[root@VM_12_22_centos testdb]# ll -h
total 232M
-rw-r----- 1 mysql mysql   61 Aug 10 13:07 db.opt
-rw-r----- 1 mysql mysql 8.5K Aug 10 13:16 student.frm
-rw-r----- 1 mysql mysql 153M Aug 10 15:37 student.MYD
-rw-r----- 1 mysql mysql  79M Aug 10 15:37 student.MYI
```
由此可见,索引文件会占用比较大的空间,但是索引的存在可以有效提升查询速度  
这里做一个测试,student表有索引,student_noindex表没有索引,数据量都是8百万,两张表的结构除了student_id属性有无索引不同之外其他完全相同
```
mysql> select * from student where student_id = 100000;
+------------+--------------+-------------+
| student_id | student_name | student_age |
+------------+--------------+-------------+
|     100000 | ciMiFC       |          28 |
+------------+--------------+-------------+
1 row in set (0.00 sec)
mysql> select * from student_noindex where student_id = 100000;
+------------+--------------+-------------+
| student_id | student_name | student_age |
+------------+--------------+-------------+
|     100000 | KZSoRt       |          16 |
+------------+--------------+-------------+
1 row in set (0.88 sec)
```
比较两次查询时间可知,索引的确可以有效提升查询速度