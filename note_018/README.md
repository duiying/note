# Vmware安装Centos7.5初始化操作

```
* 网络适配器选择 NAT模式
* 设置网卡启动,将ONBOOT=no改为ONBOOT=yes
    vi /etc/sysconfig/network-scripts/ifcfg-xxx
* 重启网络服务
    service network restart
* 为了使用ifconfig命令,需要先安装net-tools
    yum -y install net-tools
* 安装vim
    yum -y install vim
```