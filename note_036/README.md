# Ansible Playbook 常用模块

> 环境准备  

- Centos7Ansible主机192.168.2.156 (已经安装Ansible) ([Ansible简介与安装](../note_034))
- Centos7部署主机192.168.2.157 (已经初始化完成, 并已关闭selinux和firewalld) ([Vmware安装Centos7.5初始化操作](../note_018))  

> 登录部署主机  

```
# 添加wyx用户
[root@localhost ~]# useradd wyx
# 添加deploy用户
[root@localhost ~]# useradd deploy
```

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
|------------files                      文件目录
|----------------wyx.sh                 脚本文件
|------------templates                  模板文件目录
|----------------nginx.conf.j2          模板文件
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
wyx.sh
```
echo "this is a test script"
```
nginx.conf.j2
```
user              {{ user }};
worker_processes  {{ worker_processes }};

error_log  /var/log/nginx/error.log;

pid        /var/run/nginx.pid;

events {
    worker_connections  {{ max_open_file }};
}


http {
    include       /etc/nginx/mime.types;
    default_type  application/octet-stream;

    log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
                      '$status $body_bytes_sent "$http_referer" '
                      '"$http_user_agent" "$http_x_forwarded_for"';

    access_log  /var/log/nginx/access.log  main;

    sendfile        on;
    #tcp_nopush     on;

    #keepalive_timeout  0;
    keepalive_timeout  65;

    #gzip  on;

    # Load config files from the /etc/nginx/conf.d directory
    # The default server is in conf.d/default.conf
    #include /etc/nginx/conf.d/*.conf;
    server {
        listen       {{ port }} default_server;
        server_name  {{ server_name }};

        #charset koi8-r;

        #access_log  logs/host.access.log  main;

        location / {
            root   {{ root }};
            index  index.html index.htm;
        }

        error_page  404              /404.html;
        location = /404.html {
            root   /usr/share/nginx/html;
        }

        # redirect server error pages to the static page /50x.html
        #
        error_page   500 502 503 504  /50x.html;
        location = /50x.html {
            root   /usr/share/nginx/html;
        }

    }

}
```

> file模块: 在目标主机创建文件或目录, 并赋予系统权限

在roles/test/tasks/main.yml文件末尾添加下面内容
```
- name: create a file
  file: 'path=/root/wyx.txt state=touch mode=0755 owner=wyx group=wyx'
```
执行playbook
```
# 执行playbook
(.py3-a2.5-env) [deploy@localhost playbook]$ ansible-playbook -i inventory/host ./deploy.yml
# 查看部署主机文件
(.py3-a2.5-env) [deploy@localhost playbook]$ ssh root@test.example.com ls -l /root/wyx.txt
-rwxr-xr-x 1 wyx wyx 0 Jan 31 20:33 /root/wyx.txt
```

> copy模块: 实现Ansible服务端向目标主机的文件传送  

在roles/test/tasks/main.yml文件末尾添加下面内容
```
- name: copy a file
  copy: 'remote_src=no src=/home/deploy/playbook/roles/test/files/wyx.sh dest=/root/wyx.sh mode=0644 force=yes'
```
执行playbook
```
# 执行playbook
(.py3-a2.5-env) [deploy@localhost playbook]$ ansible-playbook -i inventory/host ./deploy.yml
# 查看部署主机文件
(.py3-a2.5-env) [deploy@localhost playbook]$ ssh root@test.example.com cat /root/wyx.sh
echo "this is a test script"
```

> stat模块: 获取远程文件状态信息 debug模块: 打印语句并输出到Ansible

在roles/test/tasks/main.yml文件末尾添加下面内容
```
- name: check if wyx.sh exists
  stat: 'path=/root/wyx.sh'
  register: script_stat

- debug: msg="wyx.sh exists"
  when: script_stat.stat.exists
```
执行playbook
```
# 执行playbook
(.py3-a2.5-env) [deploy@localhost playbook]$ ansible-playbook -i inventory/host ./deploy.yml
# 控制台输出
ok: [test.example.com] => {
    "msg": "wyx.sh exists"
}
```

> command/shell模块: 在目标主机执行命令行(推荐使用shell)  

在roles/test/tasks/main.yml文件末尾添加下面内容
```
- name: use command run the script
  command: 'sh /root/wyx.sh'

- name: use shell run the script
  shell: "echo 'test' > test.txt"
```
执行playbook
```
# 执行playbook
(.py3-a2.5-env) [deploy@localhost playbook]$ ansible-playbook -i inventory/host ./deploy.yml
# 查看部署主机文件
(.py3-a2.5-env) [deploy@localhost playbook]$ ssh root@test.example.com cat /root/test.txt
test
```

> template模块: 实现Ansible服务端到目标主机的jinja2模板传送  
> Packaging模块: 调用目标主机的系统包管理工具(yum, apt)进行软件安装  
> service模块: 管理目标主机系统服务  

准备工作
```
# 在部署机上创建目录
[root@localhost ~]# mkdir /etc/nginx
# 在部署机上安装Nginx源
[root@localhost ~]# rpm -Uvh http://nginx.org/packages/centos/7/noarch/RPMS/nginx-release-centos-7-0.el7.ngx.noarch.rpm
```
在inventory/host文件末尾添加下面内容
```
server_name=test.example.com
port=80
user=deploy
worker_processes=4
max_open_file=65505
root=/www
```
在roles/test/tasks/main.yml文件末尾添加下面内容
```
- name: write the nginx config file
  template: src=roles/test/templates/nginx.conf.j2 dest=/etc/nginx/nginx.conf
  
- name: ensure nginx is at the latest version
  yum: pkg=nginx state=latest
  
- name: start nginx service
  service: name=nginx state=started
```
执行playbook
```
# 执行playbook
(.py3-a2.5-env) [deploy@localhost playbook]$ ansible-playbook -i inventory/host ./deploy.yml
# 查看部署主机文件
(.py3-a2.5-env) [deploy@localhost playbook]$ ssh root@test.example.com cat /etc/nginx/nginx.conf
user              deploy;
worker_processes  4;

error_log  /var/log/nginx/error.log;

pid        /var/run/nginx.pid;

events {
    worker_connections  65505;
}


http {
    include       /etc/nginx/mime.types;
    default_type  application/octet-stream;

    log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
                      '$status $body_bytes_sent "$http_referer" '
                      '"$http_user_agent" "$http_x_forwarded_for"';

    access_log  /var/log/nginx/access.log  main;

    sendfile        on;
    #tcp_nopush     on;

    #keepalive_timeout  0;
    keepalive_timeout  65;

    #gzip  on;

    # Load config files from the /etc/nginx/conf.d directory
    # The default server is in conf.d/default.conf
    #include /etc/nginx/conf.d/*.conf;
    server {
        listen       80 default_server;
        server_name  test.example.com;

        #charset koi8-r;

        #access_log  logs/host.access.log  main;

        location / {
            root   /www;
            index  index.html index.htm;
        }

        error_page  404              /404.html;
        location = /404.html {
            root   /usr/share/nginx/html;
        }

        # redirect server error pages to the static page /50x.html
        #
        error_page   500 502 503 504  /50x.html;
        location = /50x.html {
            root   /usr/share/nginx/html;
        }

    }

}
# 查看部署主机nginx服务启动情况
(.py3-a2.5-env) [deploy@localhost playbook]$ ssh root@test.example.com ps -ef | grep nginx
root      15963      1  0 22:00 ?        00:00:00 nginx: master process /usr/sbin/nginx -c /etc/nginx/nginx.conf
deploy    15964  15963 10 22:00 ?        00:00:10 nginx: worker process
deploy    15965  15963 10 22:00 ?        00:00:11 nginx: worker process
deploy    15966  15963  0 22:00 ?        00:00:00 nginx: worker process
deploy    15967  15963  7 22:00 ?        00:00:08 nginx: worker process
```

### 总结