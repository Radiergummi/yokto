# Rewriting
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L,QSA]

# Rewrite all assets to get shorter links (like "/css/style.css")
RewriteRule ^(css|js|img|icon)/(.*)$ /public/assets/$1/$2 [L,QSA]

# Rewrite all requests for real file paths
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# Rewrite all requests to index.php
RewriteRule . /index.php [L,QSA]

</IfModule>
