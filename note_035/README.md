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
```
(.py3-a2.5-env) [deploy@localhost ~]$ ls
playbook
```
  
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
  shell: "echo 'Currently {{ user }} is loggging {{ server_name }}' > {{ output }}"
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
> 配置SSH免秘钥认证  

Ansible主机
```
# 编辑hosts文件
[root@localhost ~]# su
[root@localhost ~]# vim /etc/hosts
192.168.2.157 test.example.com
# 切换到deploy用户
[root@localhost ~]# su - deploy
# 生成rsa秘钥(一路回车)
[deploy@localhost ~]$ ssh-keygen -t rsa
# 将本地的秘钥复制到目标主机
[deploy@localhost ~]$ ssh-copy-id -i /home/deploy/.ssh/id_rsa.pub root@test.example.com
# 使用ssh连接目标主机, 此时不需要输入密码
[deploy@localhost ~]$ ssh root@test.example.com
```
> 执行playbook  

```
# 执行命令
(.py3-a2.5-env) [deploy@localhost playbook]$ ansible-playbook -i inventory/host ./deploy.yml 

PLAY [servers] ********************************************************************************

TASK [Gathering Facts] ************************************************************************
ok: [test.example.com]

TASK [test : Print server name and user to remote testbox] ************************************
changed: [test.example.com]

PLAY RECAP ************************************************************************************
test.example.com           : ok=2    changed=1    unreachable=0    failed=0   
# 在目标主机中查看文件内容
(.py3-a2.5-env) [deploy@localhost playbook]$ ssh root@test.example.com cat /root/test.txt
Currently root is loggging test.example.com
```
至此, 可以发现已经成功地在远程被部署主机test.example.com上创建了一个test.txt文件, 且文件内容和预先设置的相同