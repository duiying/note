# Vmware安装Centos7.3并搭建LNMP
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