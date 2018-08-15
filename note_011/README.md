# MySQL索引
![索引](https://raw.githubusercontent.com/duiying/note/master/img/suoyin.png) 
### 创建索引的三种方式
```
* 1. 创建表的时候创建索引
* 语法:
CREATE TABLE 表名 (字段名 数据类型 [约束条件],
                  字段名 数据类型 [约束条件],
                  [UNIQUE|PRIMARY|FULLTEXT] INDEX|KEY [别名] (字段名1 [(长度)] [ASC|DESC])
                 );
* 语法解释:
UNIQUE: 可选参数,表示唯一索引
PRIMARY: 可选参数,表示主键索引
FULLTEXT: 可选参数,表示全文索引
INDEX和KEY: 用来表示字段的索引,二选一即可(PRIMARY后面只能跟KEY而不能跟INDEX)
别名: 可选参数,表示创建的索引的名称
字段名1: 指定索引对应字段的名称
长度: 可选参数,表示索引的长度
ASC和DESC: 可选参数,ASC表示升序排列,DESC表示降序排列

* 2. 使用 CREATE INDEX 语句在已经存在的表上创建索引(该方式不支持主键索引)
* 语法
CREATE [UNIQUE|FULLTEXT] INDEX 索引名 ON 表名 (字段1 [(长度)] [ASC|DESC]);
* 3. 使用 ALTER TABLE 语句在已经存在的表上创建索引
* 语法
ALTER TABLE 表名 ADD [UNIQUE|PRIMARY|FULLTEXT] INDEX|KEY [别名] (字段名1 [(长度)] [ASC|DESC])
* 语法解释
INDEX和KEY: 用来表示字段的索引,二选一即可(PRIMARY后面只能跟KEY而不能跟INDEX)
```
### 1. 创建表的时候创建索引
#### 1.1 普通索引
在t1表中id字段上建立索引
```
CREATE TABLE t1 (id INT,
                 name VARCHAR(20),
                 INDEX (id)
                );
```
#### 1.2 唯一索引
在t2表中id字段上建立索引名为unique_id的唯一性索引,并且按照升序排列
```
CREATE TABLE t2 (id INT NOT NULL,
                 name VARCHAR(20) NOT NULL,
                 score FLOAT,
                 UNIQUE INDEX unique_id (id ASC)
                );
```
#### 1.3 全文索引
在t3表中name字段上建立索引名为fulltext_name的全文索引
```
CREATE TABLE t3 (id INT NOT NULL,
                 name VARCHAR(20) NOT NULL,
                 score FLOAT,
                 FULLTEXT INDEX fulltext_name(name)
                )ENGINE=MyISAM;
```
#### 1.4 主键索引
在t4表中id字段上建立主键索引
```
CREATE TABLE t4 (id INT,
                 name VARCHAR(20),
                 PRIMARY KEY (id)
                );
```
#### 1.5 组合索引
在t5表中id和name字段上建立索引名为multi的组合索引
```
CREATE TABLE t5 (id INT NOT NULL,
                 name VARCHAR(20) NOT NULL,
                 score FLOAT,
                 INDEX multi (id,name(20))
                );
```
### 2. 使用 CREATE INDEX 语句在已经存在的表上创建索引
#### 2.1 普通索引
先创建一个没有索引的book1表,再在book1表中bookid字段上建立索引名为index_id的普通索引
```
CREATE TABLE book1 (bookid INT NOT NULL,
                    bookname VARCHAR(255) NOT NULL,
                    info VARCHAR(255) NULL
                   );
CREATE INDEX index_id ON book1 (bookid);
```
#### 2.2 唯一索引
先创建一个没有索引的book2表,再在book2表中bookid字段上建立索引名为uniqueidx的唯一索引
```
CREATE TABLE book2 (bookid INT NOT NULL,
                    bookname VARCHAR(255) NOT NULL,
                    info VARCHAR(255) NULL
                   );
CREATE UNIQUE INDEX uniqueidx ON book2 (bookid);
```
#### 2.3 全文索引
先创建一个没有索引的book3表,再在book3表中info字段上建立索引名为fulltextidx的全文索引
```
CREATE TABLE book3 (bookid INT NOT NULL,
                    bookname VARCHAR(255) NOT NULL,
                    info VARCHAR(255) NULL
                   )ENGINE=MyISAM;
CREATE FULLTEXT INDEX fulltextidx ON book3 (info);
```
#### 2.4 主键索引
不支持
#### 2.5 组合索引
先创建一个没有索引的book4表,再在book4表中bookname和info字段上建立索引名为multi的组合索引
```
CREATE TABLE book4 (bookid INT NOT NULL,
                    bookname VARCHAR(255) NOT NULL,
                    info VARCHAR(255) NULL
                   );
CREATE INDEX multi ON book4 (bookname,info);
```
### 3. 使用 ALTER TABLE 语句在已经存在的表上创建索引
#### 3.1 普通索引
先创建一个没有索引的book11表,再在book11表中bookid字段上建立索引名为index_id的普通索引
```
CREATE TABLE book11 (bookid INT NOT NULL,
                     bookname VARCHAR(255) NOT NULL,
                     info VARCHAR(255) NULL
                    );
ALTER TABLE book11 ADD KEY index_id (bookid);
```
#### 3.2 唯一索引
先创建一个没有索引的book12表,再在book12表中bookid字段上建立索引名为uniqueidx的唯一索引
```
CREATE TABLE book12 (bookid INT NOT NULL,
                     bookname VARCHAR(255) NOT NULL,
                     info VARCHAR(255) NULL
                    );
ALTER TABLE book12 ADD UNIQUE KEY uniqueidx (bookid);
```
#### 3.3 全文索引
先创建一个没有索引的book13表,再在book13表中info字段上建立索引名为fulltextidx的全文索引
```
CREATE TABLE book13 (bookid INT NOT NULL,
                     bookname VARCHAR(255) NOT NULL,
                     info VARCHAR(255) NULL
                    )ENGINE=MyISAM;
ALTER TABLE book13 ADD FULLTEXT KEY fulltextidx (info);
```
#### 3.4 主键索引
先创建一个没有索引的book14表,再在book14表中bookid字段上建立主键索引
```
CREATE TABLE book14 (bookid INT NOT NULL,
                     bookname VARCHAR(255) NOT NULL,
                     info VARCHAR(255) NULL
                    );
ALTER TABLE book14 ADD PRIMARY KEY (bookid);
```
#### 2.5 组合索引
先创建一个没有索引的book15表,再在book15表中bookname和info字段上建立索引名为multi的组合索引
```
CREATE TABLE book15 (bookid INT NOT NULL,
                     bookname VARCHAR(255) NOT NULL,
                     info VARCHAR(255) NULL
                    );
ALTER TABLE book15 ADD KEY multi (bookname,info);
```