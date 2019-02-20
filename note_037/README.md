# Gitlab的简介/安装/使用流程

### Gitlab介绍

> Gitlab简介

- 概念: Ruby语言开发的开源分布式版本控制系统
- 功能: 管理项目源代码/版本控制/代码复用和查找

> Gitlab的优势  

- 开源免费, 适合中小型公司将代码放置在该系统中
- 差异化的版本管理, 离线同步以及强大的分支管理功能
- 便捷的GUI操作界面以及强大的账户权限管理功能
- 集成度很高, 能够集成绝大多数开发工具
- 支持内置HA, 保证在高并发下仍旧高可用

> Gitlab主要服务构成  

- Nginx 静态web服务器
- Gitlab-webhorse 轻量级的反向代理服务器
- Gitlab-shell 用于处理Git命令和修改authorized keys列表
- Logrotate 日志文件管理工具
- Postgresql 数据库
- Redis 缓存服务器

> Gitlab的工作流程  

- 创建并克隆项目
- 创建项目分支
- 编写代码并提交至该分支
- 推送该分支到Gitlab服务器
- 进行代码检查并提交Master主分支合并申请
- 项目领导审查代码并确认合并申请

### Gitlab安装流程

> 环境准备  

- Centos7主机(已经初始化完成, 并已关闭selinux和firewalld) ([Vmware安装Centos7.5初始化操作](../note_018))  

> 安装步骤  

安装
```
# 安装postfix邮件服务依赖并启动
[root@localhost ~]# yum -y install postfix
[root@localhost ~]# systemctl start postfix && systemctl enable postfix
# 安装其它依赖
[root@localhost ~]# yum -y install curl policycoreutils openssh-server openssh-clients
# 配置gitlab社区版yum仓库
[root@localhost ~]# curl -sS https://packages.gitlab.com/install/repositories/gitlab/gitlab-ce/script.rpm.sh | sudo bash
# 安装gitlab社区版
[root@localhost ~]# yum install -y gitlab-ce
```
创建证书与配置加载
```
# 新建目录
[root@localhost ~]# mkdir -p /etc/gitlab/ssl 
# 创建私有秘钥
[root@localhost ~]# openssl genrsa -out "/etc/gitlab/ssl/gitlab.example.com.key" 2048
# 创建私有证书
[root@localhost ~]# openssl req -new -key "/etc/gitlab/ssl/gitlab.example.com.key" -out "/etc/gitlab/ssl/gitlab.example.com.csr"
You are about to be asked to enter information that will be incorporated
into your certificate request.
What you are about to enter is what is called a Distinguished Name or a DN.
There are quite a few fields but you can leave some blank
For some fields there will be a default value,
If you enter '.', the field will be left blank.
-----
Country Name (2 letter code) [XX]:cn
State or Province Name (full name) []:bj
Locality Name (eg, city) [Default City]:bj
Organization Name (eg, company) [Default Company Ltd]:  # 回车
Organizational Unit Name (eg, section) []:  # 回车
Common Name (eg, your name or your server's hostname) []:gitlab.example.com
Email Address []:admin@example.com

Please enter the following 'extra' attributes
to be sent with your certificate request
A challenge password []:123456
An optional company name []:  # 回车
# 查看
[root@localhost ~]# ll /etc/gitlab/ssl/
total 8
-rw-r--r-- 1 root root 1074 Feb 21 02:26 gitlab.example.com.csr
-rw-r--r-- 1 root root 1675 Feb 21 02:25 gitlab.example.com.key
# 利用私有秘钥和私有证书来创建CRT签署证书
[root@localhost ~]# openssl x509 -req -days 365 -in "/etc/gitlab/ssl/gitlab.example.com.csr" -signkey "/etc/gitlab/ssl/gitlab.example.com.key" -out "/etc/gitlab/ssl/gitlab.example.com.crt"
# 查看
[root@localhost ~]# ll /etc/gitlab/ssl/
total 12
-rw-r--r-- 1 root root 1281 Feb 21 02:31 gitlab.example.com.crt
-rw-r--r-- 1 root root 1074 Feb 21 02:26 gitlab.example.com.csr
-rw-r--r-- 1 root root 1675 Feb 21 02:25 gitlab.example.com.key
# 利用openssl命令输出pem证书
[root@localhost ~]# openssl dhparam -out /etc/gitlab/ssl/dhparam.pem 2048
# 查看
[root@localhost ~]# ll /etc/gitlab/ssl/
total 16
-rw-r--r-- 1 root root  424 Feb 21 02:35 dhparam.pem
-rw-r--r-- 1 root root 1281 Feb 21 02:31 gitlab.example.com.crt
-rw-r--r-- 1 root root 1074 Feb 21 02:26 gitlab.example.com.csr
-rw-r--r-- 1 root root 1675 Feb 21 02:25 gitlab.example.com.key
```
配置
```
# 备份配置文件
[root@localhost gitlab]# cp /etc/gitlab/gitlab.rb{,.bak}
# 修改配置文件
[root@localhost gitlab]# vim /etc/gitlab/gitlab.rb
[root@localhost gitlab]# diff /etc/gitlab/gitlab.rb /etc/gitlab/gitlab.rb.bak 
13c13
< external_url 'https://gitlab.example.com'
---
> external_url 'http://gitlab.example.com'
953c953
< nginx['redirect_http_to_https'] = true
---
> # nginx['redirect_http_to_https'] = false
965,966c965,966
< # nginx['ssl_certificate'] = "/etc/gitlab/ssl/gitlab.example.com.crt"
< # nginx['ssl_certificate_key'] = "/etc/gitlab/ssl/gitlab.example.com.key"
---
> # nginx['ssl_certificate'] = "/etc/gitlab/ssl/#{node['fqdn']}.crt"
> # nginx['ssl_certificate_key'] = "/etc/gitlab/ssl/#{node['fqdn']}.key"
980c980
< # nginx['ssl_dhparam'] = /etc/gitlab/ssl/dhparams.pem # Path to dhparams.pem, eg. /etc/gitlab/ssl/dhparams.pem
---
> # nginx['ssl_dhparam'] = nil # Path to dhparams.pem, eg. /etc/gitlab/ssl/dhparams.pem
# 使配置生效
[root@localhost gitlab]# gitlab-ctl reconfigure
Running handlers complete
Chef Client finished, 454/655 resources updated in 02 minutes 07 seconds
gitlab Reconfigured!
# 出现这个表示配置没有问题

# 配置Nginx
[root@localhost gitlab]# cp /var/opt/gitlab/nginx/conf/gitlab-http.conf{,.bak}
[root@localhost gitlab]# vim /var/opt/gitlab/nginx/conf/gitlab-http.conf
# 在37行 server_name gitlab.example.com; 下面新增一行
rewrite ^(.*)$ https://$host$1 permanent;

# 重启Gitlab
[root@localhost gitlab]# gitlab-ctl restart
```
访问
```
# 配置hosts
192.168.246.128 gitlab.example.com
# 浏览器访问 gitlab.example.com
```
首次访问需要设置root用户的密码  
![Gitlab第一次访问](https://raw.githubusercontent.com/duiying/note/master/img/gitlab-index.png)  
使用root和12345678登录, 进入欢迎页面    
![Gitlab welcome](https://raw.githubusercontent.com/duiying/note/master/img/gitlab-welcome.png)  
创建测试项目  
![Gitlab create](https://raw.githubusercontent.com/duiying/note/master/img/gitlab-create.png)  
克隆项目(需要输入用户名root和密码12345678)  
$ git -c http.sslverify=false clone https://gitlab.example.com/root/test.git  
新建文件并推送到Gitlab服务器  
```
wyx@DESKTOP-EHP4TTA MINGW64 ~/Desktop
$ cd test/

wyx@DESKTOP-EHP4TTA MINGW64 ~/Desktop/test (master)
$ echo hello >> 1.txt

wyx@DESKTOP-EHP4TTA MINGW64 ~/Desktop/test (master)
$ git add .
warning: LF will be replaced by CRLF in 1.txt.
The file will have its original line endings in your working directory.

wyx@DESKTOP-EHP4TTA MINGW64 ~/Desktop/test (master)
$ git commit -m "first commit"
[master (root-commit) 4c56cb6] first commit
 1 file changed, 1 insertion(+)
 create mode 100644 1.txt

wyx@DESKTOP-EHP4TTA MINGW64 ~/Desktop/test (master)
$ git -c http.sslverify=false commit origin master
error: pathspec 'origin' did not match any file(s) known to git.
error: pathspec 'master' did not match any file(s) known to git.

wyx@DESKTOP-EHP4TTA MINGW64 ~/Desktop/test (master)
$ git -c http.sslverify=false push origin master
Counting objects: 3, done.
Writing objects: 100% (3/3), 206 bytes | 206.00 KiB/s, done.
Total 3 (delta 0), reused 0 (delta 0)
To https://gitlab.example.com/root/test.git
 * [new branch]      master -> master

```

 

