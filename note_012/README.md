# Vmware安装Centos7.3并搭建LNMP以及配置TP5.1
### 安装Centos7.3
```
* 镜像下载地址
    http://archive.kernel.org/centos-vault/7.3.1611/isos/x86_64/CentOS-7-x86_64-Minimal-1611.iso
* 在Vmware中安装
    网络适配器选择 NAT模式(用于共享主机的IP地址)
```
### 使用xshell连接Centos
```
* 查看防火墙状态
    service firewalld status
* 关闭防火墙
    service firewalld stop
* 要查看IP,Centos7.3 mini版本中默认情况下没有 ifconfig 命令,而是用 ip addr 命令来查看IP
* 设置网卡启动,将ONBOOT=no改为ONBOOT=yes
    vi /etc/sysconfig/network-scripts/ifcfg-xxx
* 重启网络服务
    service network restart
* 使用 ip addr 命令查看ip
[root@localhost ~]# ip addr
1: lo: <LOOPBACK,UP,LOWER_UP> mtu 65536 qdisc noqueue state UNKNOWN qlen 1
    link/loopback 00:00:00:00:00:00 brd 00:00:00:00:00:00
    inet 127.0.0.1/8 scope host lo
       valid_lft forever preferred_lft forever
    inet6 ::1/128 scope host 
       valid_lft forever preferred_lft forever
2: ens33: <BROADCAST,MULTICAST,UP,LOWER_UP> mtu 1500 qdisc pfifo_fast state UP qlen 1000
    link/ether 00:0c:29:f0:65:1a brd ff:ff:ff:ff:ff:ff
    inet 192.168.159.129/24 brd 192.168.159.255 scope global dynamic ens33
       valid_lft 1667sec preferred_lft 1667sec
    inet6 fe80::faf5:ace0:d5c7:fade/64 scope link 
       valid_lft forever preferred_lft forever
* 其中 192.168.159.129 就是我们要连接的ip地址,有了IP地址就去使用xshell进行连接

* 也可以使用ifconfig命令,需要先安装net-tools
    yum -y install net-tools
* 安装好net-tools之后,再使用ifconfig就可以查看IP了
[root@localhost ~]# ifconfig
ens33: flags=4163<UP,BROADCAST,RUNNING,MULTICAST>  mtu 1500
        inet 192.168.159.129  netmask 255.255.255.0  broadcast 192.168.159.255
        inet6 fe80::faf5:ace0:d5c7:fade  prefixlen 64  scopeid 0x20<link>
        ether 00:0c:29:f0:65:1a  txqueuelen 1000  (Ethernet)
        RX packets 9844  bytes 12894824 (12.2 MiB)
        RX errors 0  dropped 0  overruns 0  frame 0
        TX packets 1455  bytes 122030 (119.1 KiB)
        TX errors 0  dropped 0 overruns 0  carrier 0  collisions 0

lo: flags=73<UP,LOOPBACK,RUNNING>  mtu 65536
        inet 127.0.0.1  netmask 255.0.0.0
        inet6 ::1  prefixlen 128  scopeid 0x10<host>
        loop  txqueuelen 1  (Local Loopback)
        RX packets 16  bytes 1392 (1.3 KiB)
        RX errors 0  dropped 0  overruns 0  frame 0
        TX packets 16  bytes 1392 (1.3 KiB)
        TX errors 0  dropped 0 overruns 0  carrier 0  collisions 0

```
### 修改系统镜像源为网易镜像源
```
* 首先安装wget
    yum -y install wget
* 备份/etc/yum.repos.d/CentOS-Base.repo
    mv /etc/yum.repos.d/CentOS-Base.repo /etc/yum.repos.d/CentOS-Base.repo.backup
* 下载对应版本repo文件, 放入/etc/yum.repos.d/
    cd /etc/yum.repos.d/
    wget http://mirrors.163.com/.help/CentOS7-Base-163.repo
* 运行以下命令生成缓存
    yum clean all
    yum makecache
```
### 安装nginx
```
* 进入目录
    cd /usr/src
* 下载nginx包
    wget http://nginx.org/packages/centos/7/noarch/RPMS/nginx-release-centos-7-0.el7.ngx.noarch.rpm
* 安装yum源
   rpm -ivh nginx-release-centos-7-0.el7.ngx.noarch.rpm
* yum安装nginx
    yum -y install nginx
```
### 安装MySQL
```
* 查看系统中是否安装过MySQL
    rpm -qa | grep mysql 或者 yum list installed | grep mysql
* 如果安装过MySQL,先卸载
    yum -y remove mysql
* 进入目录
    cd /usr/src
* 下载MySQL包
    wget http://repo.mysql.com/mysql57-community-release-el7-8.noarch.rpm
* 安装yum源
    rpm -ivh mysql57-community-release-el7-8.noarch.rpm
* 查看可用的MySQL包
    yum search mysql
* 安装MySQL
    yum -y install mysql-community-server
* 启动MySQL
    service mysqld start/stop/restart
* 查看进程
    ps -ef | grep mysql
* MySQL安装完成之后,在/var/log/mysqld.log文件中给root生成了一个默认密码,利用查找出来的密码登录MySQL
    cat /var/log/mysqld.log | grep password
* 修改root密码(mysql5.7默认安装了密码安全检查插件（validate_password),默认密码检查策略要求密码必须包含:大小写字母、数字和特殊符号,并且长度不能少于8位.)
    mysql> set password = password('WYX*wyx123');
* 开放MySQL远程连接(%表示允许任何主机连接)

    mysql> use mysql;
    mysql> select host,user from user;
    mysql> update user set host = '%' where host = 'localhost' and user = 'root';
    mysql> flush privileges;
```
### 安装PHP
```
* 首先查看系统yum自带的PHP版本信息,发现PHP版本是5.4,有点低,需要安装其他yum源
    yum info php
* 安装epel源(epel是基于Fedora的一个项目,为"红帽系"的操作系统提供额外的软件包)
    rpm -ivh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm
* 安装webtatic源(安装webtatic-release之前需要先安装epel-release)
    rpm -ivh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm
* 查询安装过的webtatic包名
    [root@localhost src]# rpm -qa | grep webtatic
    webtatic-release-7-3.noarch
* 如果要卸载
    rpm -e webtatic-release
* 查看webtatic源中PHP的版本,发现有很多可选版本,这里选择PHP7.2
    [root@localhost src]# yum search php | grep fpm
    php-fpm.x86_64 : PHP FastCGI Process Manager
    php55w-fpm.x86_64 : PHP FastCGI Process Manager
    php56w-fpm.x86_64 : PHP FastCGI Process Manager
    php70w-fpm.x86_64 : PHP FastCGI Process Manager
    php71w-fpm.x86_64 : PHP FastCGI Process Manager
    php72w-fpm.x86_64 : PHP FastCGI Process Manager
* 查看PHP可用的包
    yum search php72w 或者 yum list | grep php72w
* 安装PHP及其扩展
    yum -y install mod_php72w php72w-bcmath php72w-cli php72w-common php72w-devel php72w-fpm php72w-gd php72w-mbstring php72w-mysql php72w-opcache php72w-pdo php72w-xml
* 启动服务
    service php-fpm start
* 查看进程
    [root@localhost src]# ps -ef | grep fpm
    root      21204      1  5 18:54 ?        00:00:00 php-fpm: master process (/etc/php-fpm.conf)
    apache    21205  21204  0 18:54 ?        00:00:00 php-fpm: pool www
    apache    21206  21204  0 18:54 ?        00:00:00 php-fpm: pool www
    apache    21207  21204  0 18:54 ?        00:00:00 php-fpm: pool www
    apache    21208  21204  0 18:54 ?        00:00:00 php-fpm: pool www
    apache    21209  21204  0 18:54 ?        00:00:00 php-fpm: pool www
    root      21211  10666  0 18:54 pts/0    00:00:00 grep --color=auto fpm
````		
### 配置nginx使其支持PHP
```
* 安装vim
    yum -y install vim
* 进入目录
    cd /etc/nginx/conf.d
* 删除default.conf并新建my.conf
    server {
        listen       80;
        server_name  wyx.com;
        root /data/www;
        index index.html index.htm index.php;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location ~ \.php$ {
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include fastcgi_params;
            try_files $uri =404;
        }
    }
* 重启nginx
    service nginx restart
* 新建目录
    mkdir -p /data/www
* 更改目录权限
    chmod -R 777 /data/www
* 新建文件index.php
    vim /data/www/index.php
    * index.php文件内容      
        <?php
            phpinfo();
* 修改本地hosts(C:\Windows\System32\drivers\etc\hosts),新增一行
    192.168.159.129 wyx.com
* 浏览器访问wyx.com,发现报错403,是selinux没有关闭的原因,于是关闭selinux
    setenforce 0
* 此时wyx.com便会显示phpinfo的内容
```
### 使用composer下载TP5.1
```
* 安装composer
    * 下载composer.phar
        curl -sS https://getcomposer.org/installer | php
    * 可以通过--install-dir选项指定composer的安装目录
        curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/src/composer
    * 把composer.phar放在系统的PATH目录中,就能在全局访问composer.phar
        mv /usr/src/composer/composer.phar /usr/local/bin/composer
    * 现在只需要运行composer命令就可以使用composer而不需要输入php composer.phar
        [root@localhost www]# composer -V
        Composer version 1.7.2 2018-08-16 16:57:12
    * 使用国内镜像
        composer config -g repo.packagist composer https://packagist.phpcomposer.com
        或者
        composer config -g repo.packagist composer https://packagist.laravel-china.org
* 下载TP5.1
    * 进入目录
        cd /data/www
    * 下载(tp5是自定义的目录名称)
        composer create-project topthink/think tp5
    * 浏览器访问http://wyx.com/index.php?s=index/index/hello,页面显示
        hello,ThinkPHP5
```
### 配置nginx使其支持TP5.1的pathinfo模式,并隐藏入口文件index.php
```
* 编辑配置文件
    vim /etc/nginx/conf.d/my.conf
        server {
            listen       80;
            server_name  wyx.com;
            root /data/www/tp5/public;
            index index.html index.htm index.php;

            location / {
                if (!-e $request_filename) {
                    rewrite  ^(.*)$  /index.php?s=$1  last;
                    break;
                }
            }

            location ~ .+.php($|/) {
                set $script $uri;
                set $path_info "/";
                if ($uri ~ "^(.+.php)(/.+)") {
                    set $script $1;
                    set $path_info $2;
                }
                include fastcgi_params;
                fastcgi_param PATH_INFO $path_info;
                fastcgi_index index.php?IF_REWRITE=1;
                fastcgi_pass 127.0.0.1:9000;
                fastcgi_param SCRIPT_FILENAME $document_root/$script;
                fastcgi_param SCRIPT_NAME $script;

            }
        }
* 重启nginx
    service nginx restart
* 浏览器访问http://wyx.com/index/index/hello,页面显示
    hello,ThinkPHP5
```
### TP5.1的一些修改
```
* 在入口文件中定义两个目录
    vim /data/www/tp5/public/index.php
        define('APP_PATH', dirname(__DIR__ . '/application'));
        define('SITE_PATH', dirname(__DIR__));
* 新建两个目录
    * 业务逻辑层
        mkdir -p /data/www/tp5/application/model/Biz
    * 数据层
        mkdir -p /data/www/tp5/application/model/Dao
```