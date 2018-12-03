# 如何关闭selinux

### 临时关闭selinux(重启后失效)
```
setenforce 0
```

### 永久关闭selinux
```
* 编辑配置文件
    vim /etc/sysconfig/selinux
* 将 SELINUX=enforcing 改为 SELINUX=disabled
* 重启服务器
    reboot
```

### 查看selinux状态
```
[root@localhost ~]# getenforce
Disabled
```