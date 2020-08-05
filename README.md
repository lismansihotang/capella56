# capella5.6
Capella ERP Indonesia v5.6 

Kebutuhan :
1. Yii Framework versi 1.1 versi terakhir (https://yiiframework.com/download)
2. MariaDB / MySQL
3. PHP 7.3 FPM (web server nginx) atau PHP 7.3 (Apache)
4. Web Server Apache / Nginx, keduanya dengan module standard yang dibutuhkan (rewrite, pdo mysql, mysqli, )
5. Jasper Report untuk aplikasi report

Kalau tidak mau repot, install saja Xampp (Apache + PHP 7.3 + MySQL) atau Winnmp (Nginx + PHP 7.3-FPM + MariaDB)

Instalasi :
1. Letakkan file framework di folder htdocs (apache) atau folder www (nginx)
2. Buat folder capella56 dan Letakkan di folder htdocs (apache) atau folder www (nginx)
3. Buat file .htaccess (apache) atau update nginx.conf, buat domain (nginx)

Isi domain 
server {
	listen		*:80;
	server_name 	capella56my.test;
	root 	"c:/winnmp/www/capella56my"; #disesuaikan dengan folder
	allow		all;
	#index index.php;
  autoindex on;
 
	location ~ \.php$ {
		try_files $uri =404;
		include		nginx.fastcgi.conf;
		include		nginx.redis.conf;
		fastcgi_pass	php_farm;
		fastcgi_hide_header X-Powered-By;
	}
 
	location / {
		rewrite ^/(.*)$ /index.php?url=$1 last;
    set $page_to_view "/index.php";
    try_files $uri $uri/ @rewrites;
    proxy_set_header  Host $host;
    proxy_set_header  X-Real-IP $remote_addr;
    proxy_set_header  X-Forwarded-Proto https;
    proxy_set_header  X-Forwarded-For $remote_addr;
    proxy_set_header  X-Forwarded-Host $remote_addr;
	}

	location ~ \.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar|woff|woff2|ttf|map|mp4)$ {
    try_files $uri =404;
    access_log off;
    log_not_found off;
    expires 365d;
  }
  location ~ /\.ht {
    access_log off;
    log_not_found off;
    deny all;
  }
  location @rewrites {
    if ($uri ~* ^/([a-z]+)$) {
      set $page_to_view "/$1.php";
      rewrite ^/([a-z]+)$ /$1.php last;
    }
  }
 
}

4. edit system32/drivers/etc/hosts , tambahkan capella56my.test

5. Buat Database capellafive + import file capellafive.sql

6. Edit file config/main.php bagian DB, sesuaikan dengan user, password dan host

7. Panggil aplikasi ERP di http://capella56my.test

Jika masih error, ulangi dan cek langkah sebelumnya atau improvisasi sendiri hahaha
