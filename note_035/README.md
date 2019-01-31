# Ansible Playbook上手

> 环境准备  

- Centos7Ansible主机192.168.2.156 (已经安装Ansible) ([Ansible简介与安装](../note_034))
- Centos7部署主机192.168.2.157 (已经初始化完成, 并已关闭selinux和firewalld) ([Vmware安装Centos7.5初始化操作](../note_018))  

> 登录Ansible主机  

```
# 先切换到ansible账户
[root@localhost ~]# su - deploy
# 激活虚拟环境.py3-a2.5-env
[deploy@localhost ~]$ source /home/deploy/.py3-a2.5-env/bin/activate
# 在.py3-a2.5-env虚拟环境下加载ansible2.5
(.py3-a2.5-env) [deploy@localhost ~]$ source /home/deploy/.py3-a2.5-env/ansible/hacking/env-setup -q
# 查看ansible版本
(.py3-a2.5-env) [deploy@localhost ~]$ ansible-playbook --version
ansible-playbook 2.5.14 (stable-2.5 465b848985) last updated 2019/01/31 16:23:20 (GMT +800)
```

> 编写playbook  

  
目录结构
```
playbook
|----deploy.yml                         playbook入口文件
|----inventory                          ansible可管理的主机源目录
|--------host                           声明主机和主机变量的文件
|----roles                              角色列表目录
|--------test                           具体的角色目录
|------------tasks                      运行任务列表
|----------------main.yml               test角色下的主任务文件
```
host  
- [servers] 目标主机列表
- test.example.com 目标主机名
- [servers:vars] 参数列表
- server_name user output 目标主机参数, 格式: key=value
```
[servers]
test.example.com

[servers:vars]
server_name=test.example.com
user=root
output=/root/test.txt
```
main.yml
- name 任务名称
- shell 使用shell模块
```
- name: Print server name and user to remote testbox
  shell:"echo 'Currently {{ user }} is loggging {{ server_name }}' > {{ output }}"
```
deploy.yml
- hosts 目标主机列表
- gather_facts 获取主机基本信息
- remote_user 指定目标主机用户
- roles 指定角色
```
- hosts: "servers"
  gather_facts: true
  remote_user: root
  roles:
    - test
```
 