# Ansible 简介和安装步骤

> Ansible介绍  

- 概念: Ansible是用Python语言开发的一个开源部署工具
- 特点: 使用SSH协议通讯, 全平台, 无需编译, 模块化部署管理
- 作用: 推送Playbook进行远程节点快速部署

> Ansible优势  

- 轻量级无客户端(Agentless)
- 开源免费, 学习成本低, 快速上手
- 使用playbook作为核心配置架构, 统一的脚本格式, 批量化部署
- 支持目前主流的开发场景

> Ansible安装  

初始化操作  
[Vmware安装Centos7.5初始化操作](../note_018)  

安装Python3.6.5和virtualenv
```
# 下载Python安装包
[root@localhost ~]# wget https://www.python.org/ftp/python/3.6.5/Python-3.6.5.tgz
# 解压
[root@localhost ~]# tar xf Python-3.6.5.tgz
# 进入目录
[root@localhost ~]# cd Python-3.6.5
# 配置
[root@localhost Python-3.6.5]# ./configure --prefix=/usr/local/ --with-ensurepip=install --enable-shared LDFLAGS="-Wl,-rpath /usr/local/lib"
# 编译和安装
[root@localhost Python-3.6.5]# make && make altinstall
# 报错如下
zipimport.ZipImportError: can't decompress data; zlib not available
make: *** [altinstall] Error 1

# 于是安装zlib
[root@localhost Python-3.6.5]# yum -y install zlib*
# 重新编译和安装
[root@localhost Python-3.6.5]# make && make altinstall
# 创建软链
[root@localhost Python-3.6.5]# ln -s /usr/local/bin/pip3.6 /usr/local/bin/pip
# pip安装virtualenv
[root@localhost Python-3.6.5]# pip install virtualenv
# 报错如下
connect to HTTPS URL because the SSL module is not available.",)) - skipping

# 于是安装openssl
[root@localhost Python-3.6.5]# yum -y install openssl*
# 重新配置 编译 安装
[root@localhost Python-3.6.5]# ./configure --prefix=/usr/local/ --with-ensurepip=install --enable-shared LDFLAGS="-Wl,-rpath /usr/local/lib"
[root@localhost Python-3.6.5]# make && make altinstall
# 再次使用pip安装virtualenv, 提示安装成功
[root@localhost Python-3.6.5]# pip install virtualenv
Successfully installed virtualenv-16.3.0
```
创建ansible账户并安装Python3.6.5版本virtualenv实例
```
# 创建并切换用户
[root@localhost Python-3.6.5]# useradd deploy && su - deploy
# 指定Python版本创建虚拟环境.py3-a2.5-env
[deploy@localhost ~]$ virtualenv -p /usr/local/bin/python3.6 .py3-a2.5-env
```
ansible安装
```
# 切换到root安装必要软件
[root@localhost deploy]# yum -y install git curl nss
# 切换到deploy用户并进入到之前创建的.py3-a2.5-env目录
[root@localhost deploy]# su - deploy
Last login: Thu Jan 31 15:53:50 CST 2019 on pts/0
[deploy@localhost ~]$ cd /home/deploy/.py3-a2.5-env/
# 通过git下载ansible源码
[deploy@localhost .py3-a2.5-env]$ git clone https://github.com/ansible/ansible.git
# 激活虚拟环境.py3-a2.5-env
[deploy@localhost .py3-a2.5-env]$ source /home/deploy/.py3-a2.5-env/bin/activate
# 安装依赖
(.py3-a2.5-env) [deploy@localhost .py3-a2.5-env]$ pip install paramiko PyYAML jinja2
# 查看目录
(.py3-a2.5-env) [deploy@localhost .py3-a2.5-env]$ ll
total 4
drwxrwxr-x 14 deploy deploy 4096 Jan 31 16:13 ansible
drwxrwxr-x  2 deploy deploy  288 Jan 31 15:57 bin
drwxrwxr-x  2 deploy deploy   24 Jan 31 15:54 include
drwxrwxr-x  3 deploy deploy   23 Jan 31 15:54 lib
# 进入ansible目录
(.py3-a2.5-env) [deploy@localhost .py3-a2.5-env]$ cd ansible
# 切换到ansible2.5版本
(.py3-a2.5-env) [deploy@localhost ansible]$ git checkout stable-2.5
# 当前所在目录
(.py3-a2.5-env) [deploy@localhost ansible]$ pwd
/home/deploy/.py3-a2.5-env/ansible
# 在.py3-a2.5-env虚拟环境下加载ansible2.5
(.py3-a2.5-env) [deploy@localhost ansible]$ source /home/deploy/.py3-a2.5-env/ansible/hacking/env-setup -q
```
验证安装是否成功
```
(.py3-a2.5-env) [deploy@localhost ansible]$ ansible --version
ansible 2.5.14 (stable-2.5 465b848985) last updated 2019/01/31 16:23:20 (GMT +800)
```

