# Composer入门

### 安装
#### linux
如果没有安装PHP,首先安装PHP
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
```
全局安装Composer
```
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```
现在只需要运行 composer 命令就可以使用 Composer 而不需要输入 php composer.phar  
```
* 测试
[root@VM_12_22_centos ~]# composer -V
Composer version 1.7.2 2018-08-16 16:57:12
```

#### Windows
首先在系统环境变量中设置PHP的环境变量  
下载composer.exe安装程序
```
https://getcomposer.org/Composer-Setup.exe
```
然后执行exe程序,不需要手动填写任何信息,一路next即可,但是要注意php.exe路径是否正确
```
* 测试
C:\Users\wyx>composer -V
Composer version 1.7.2 2018-08-16 16:57:12
```
### 创建composer.json文件以及在packagist.org中提交项目
创建composer.json文件
```
* 在github中创建仓库名为test
* 克隆仓库
    * 先安装git
        yum -y install git
    * 克隆
        mkdir -p /data/www
        cd /data/www/
        [root@VM_12_22_centos www]# git clone https://github.com/duiying/test.git
* 进入项目根目录
    [root@VM_12_22_centos www]# cd test
    [root@VM_12_22_centos test]# pwd
    /data/www/test
* 初始化
    [root@VM_12_22_centos test]# composer init
    * 包名设置为duiying/test
        Package name (<vendor>/<name>) [root/test]: duiying/test
    * 描述设置为test project
        Description []: test project
    * 设置作者(格式如下)
        Author [, n to skip]: wyx <1822581649@qq.com>
    * 设置最低版本类型(这里选择dev即开发版)
        Minimum Stability []: dev
    * 设置包的类型
        Package Type (e.g. library, project, metapackage, composer-plugin) []: project
    * 设置授权类型
        License []: mit
    * 设置是否依赖第三方库,如果依赖选择yes,否则选择no或者直接回车然后在search的时候再回车
        Would you like to define your dependencies (require) interactively [yes]? no
        Would you like to define your dev dependencies (require-dev) interactively [yes]? no
    * 是否生成composer.json,生成选择yes或者直接回车
        Do you confirm generation [yes]? 
    *  是否把生成的vendor目录放到.gitignore文件,如果是选择yes或者直接回车
        Would you like the vendor directory added to your .gitignore [yes]? 
* 查看composer.json文件
    [root@VM_12_22_centos test]# cat composer.json 
    {
        "name": "duiying/test",
        "description": "test project",
        "type": "project",
        "license": "mit",
        "authors": [
            {
                "name": "wyx",
                "email": "1822581649@qq.com"
            }
        ],
        "minimum-stability": "dev",
        "require": {}
    }
* 查看.gitignore文件
    [root@VM_12_22_centos test]# cat .gitignore 


    /vendor/ 
```
提交到github
```
* 设置git的用户名和邮箱
    [root@VM_12_22_centos test]# git config --global user.email "1822581649@qq.com"
    [root@VM_12_22_centos test]# git config --global user.name "wyx"
* 提交
    [root@VM_12_22_centos test]# git add .
    [root@VM_12_22_centos test]# git commit -m "composer init"
    [root@VM_12_22_centos test]# git push origin master:master
    Username for 'https://github.com': 1822581649@qq.com
    Password for 'https://1822581649@qq.com@github.com':
```
将github上的仓库提交到packagist注册
```
进入https://packagist.org  
首先要在 Packagist 上注册账号并登录（可以用 GitHub 直接登录）
点击顶部导航条中的 Summit 按钮
在输入框中输入 GitHub 上的刚才仓库地址
https://github.com/duiying/test
然后点击 Check 按钮, Packagist 会去检测此仓库地址的代码是否符合 Composer 的 Package 包的要求
检测正常的话,会出现 Submit 按钮,再点击一下 Submit 按钮,我们的包就提交到 Packagist 上了
```