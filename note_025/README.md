# Yii2

### 安装  

```
# 全局配置镜像加速
composer config -g repo.packagist composer https://packagist.laravel-china.org
# 安装Yii2 Advanced
composer create-project yiisoft/yii2-app-advanced
# 进入项目根目录
# 检查当前 PHP 环境是否满足 Yii2 最基本需求
php requirements.php
# 初始化
php init
# 配置虚拟主机
# 浏览器访问
```

### 配置数据库  

```
# common/config/main-local.php
<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2-b2c',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'tablePrefix' => 'b2c_',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
```

### 自定义允许访问Gii模块的IP

```
# frontend/config/main-local.php 
# backend/config/main-local.php
$config['modules']['gii'] = [
    'class' => 'yii\gii\Module',
    'allowedIPs' => ['127.0.0.1'],
];
# 浏览器访问 http://域名/index.php?r=gii
```

### 修改默认控制器  

```
# frontend/config/main.php
# 在 'id' => 'app-frontend', 下面新增一行
'defaultRoute' => 'index',

# backend/config/main.php
# 在 'id' => 'app-backend', 下面新增一行
'defaultRoute' => 'index',
```

### 发送邮件
```
# 1. 配置
# common/config/main-local.php
'mailer' => [
    'class' => 'yii\swiftmailer\Mailer',
    'viewPath' => '@common/mail',
    // send all mails to a file by default. You have to set
    // 'useFileTransport' to false and configure a transport
    // for the mailer to send real emails.
    'useFileTransport' => false,
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp.163.com',
        'username' => '17725027209@163.com',
        'password' => '密码',
        'port' => '465',
        'encryption' => 'ssl',
    ],
    'messageConfig' => [
        'charset' => 'UTF-8',
        'from' => ['17725027209@163.com' => 'admin']
    ],
],

# 2. 控制器或模型
$mailer = Yii::$app->mailer->compose('forget-pass', ['admin_name' => $this->admin_name, 'time' => $time, 'token' => $token]);
$mailer->setTo($this->admin_email);
$mailer->setSubject("Yii2-Admin 找回密码");
if ($mailer->send()) {
    echo '发送成功';
}

# 3. 模板
# common/mail/forget-pass.php
<p>尊敬的<?php echo $admin_name; ?>，您好：</p>

<p>您的找回密码链接如下：</p>

<?php $url = Yii::$app->urlManager->createAbsoluteUrl(['admin/forget-pass', 'time' => $time, 'admin_name' => $admin_name, 'token' => $token]); ?>
<p><a href="<?php echo $url; ?>"><?php echo $url; ?></a></p>

<p>该链接10分钟内有效，请勿传递给别人！</p>

<p>该邮件为系统自动发送，请勿回复！</p>
```

### Yii2整合AdminLTE后台主题

```
# 使用composer下载
composer require dmstr/yii2-adminlte-asset "2.*"
# 复制整个 vendor/dmstr/yii2-adminlte-asset/example-views/yiisoft/yii2-app 目录下的 layouts 目录和 site 目录到 backend/views , 覆盖原始文件

# 如何预览
# backend/controllers/目录下新建IndexController.php
<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;

/**
 * Index controller
 */
class IndexController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
# backend/views/目录下新建index目录, index目录下新建index.php
<?php
echo 'hello world';    
```
![Yii2-adminlte](https://raw.githubusercontent.com/duiying/note/master/img/Yii2-adminlte.png)  


### Nginx配置
**frontend**
```
server {
    listen 80;
    # listen [::]:80 default_server ipv6only=on;

    server_name frontend.yii2.com;
    root /data/www/yii2-adminlte/frontend/web;
    index index.php index.html index.htm;

    location / {
	try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_pass cgi:9000;
        try_files $uri =404;
    }

    location ~* /\. {
        deny all;
    }
}
```
**backend**
```
server {
    listen 80;
    # listen [::]:80 default_server ipv6only=on;

    server_name admin.yii2.com;
    root /data/www/yii2-adminlte/backend/web;
    index index.php index.html index.htm;

    location / {
	try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_pass cgi:9000;
        try_files $uri =404;
    }

    location ~* /\. {
        deny all;
    }
}
```
