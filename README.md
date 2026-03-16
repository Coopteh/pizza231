## Проект МАГАЗИНА БЫТОВОЙ ТЕХНИКИ для МЕНЯ

LEGACY .htaccess

RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ /pizza221/index.php

