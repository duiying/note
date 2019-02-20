# Vmware安装Centos7.5初始化操作

```
# 网络适配器选择 NAT模式
# 设置网卡启动,将ONBOOT=no改为ONBOOT=yes
    vi /etc/sysconfig/network-scripts/ifcfg-xxx
# 重启网络服务
    service network restart
# 为了使用ifconfig命令,需要先安装net-tools
    yum -y install net-tools
# 安装vim
    yum -y install vim
# 安装其他常用软件
    yum -y install wget gcc gcc-c++ git
```

### 如何关闭防火墙
```
# 关闭防火墙
    systemctl stop firewalld
# 开机禁用防火墙
    systemctl disable firewalld
```
### 如何关闭selinux
```
# 编辑配置文件
    vim /etc/sysconfig/selinux
# 将 SELINUX=enforcing 改为 SELINUX=disabled
# 重启服务器
    reboot
```

### 解决虚拟机只有IPV6, 没有IPV4
```
# 重启网络服务
    service network restart
```