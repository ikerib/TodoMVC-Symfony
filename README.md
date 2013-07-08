#TodoMVC
===

[TodoMVC](http://todomvc.com/ "TodoMVC") aplikazioa Symfony 2.3 PHP Framework-a erabiliz garatua.

[TodoMVC](http://todomvc.com/ "TodoMVC") created on Symfony 2.3 PHP Framework.

Aplicacion [TodoMVC](http://todomvc.com/ "TodoMVC") realizada en el Framework PHP Symfony 2.3.

###Instalatzeko - Instalation - Instalación

- git clone
- curl -sS https://getcomposer.org/installer | php
- composer install
- php app/console doctrine:database:create
- php app/console doctrine:schema:create
- chmod -R 777 app/logs app/cache
- php app/console assetic:dump --env=prod --no-debug
- php app/console assets:install web --symlink
- php app/console cache:clear --env=prod --no-debug
- Konfiguratu apache - Setup apache - Configura apache Virtual Host:

		<Directory "/Users/ikerib/www/todomvc/web/">
			Allow From All
			AllowOverride All
		</Directory>
		<VirtualHost *:80>
			ServerName "todomvc.dev"
			DocumentRoot "/Users/ikerib/www/todomvc/web"
		</VirtualHost>		
		
### Ireki / Go / Abre
[http://todomvc.dev/](http://todomvc.dev/ "TodoMVC")