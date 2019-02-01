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
[root@localhost ~]# openssl req -new -key "/etc/gitlab/ssl/gitlab.example.com.key"  -out "/etc/gitlab/gitlab.example.com.csr"

# 利用私有秘钥和私有证书来创建CRT签署证书

# 利用openssl命令输出pem证书


```