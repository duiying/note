# TP5 Nginx配置
```
# tp5.conf
server {

    listen 80 default_server;
    listen [::]:80 default_server ipv6only=on;

    server_name 域名;
    root /data/www;
    index index.php index.html index.htm;

    location / {
		if (!-e $request_filename) {
			rewrite ^(.*)$ /index.php?s=/$1 last;
			break;
		}
	}

    # deny accessing php files for the /assets directory
    location ~ ^/assets/.*\.php$ {
        deny all;
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


