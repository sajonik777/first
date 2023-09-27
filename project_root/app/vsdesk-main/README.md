**### Prepare to install prerequesties ###**
Installing ubuntu server 14.04.4 or higher

* sudo apt-get install lamp-server^
* sudo apt-get install git
* sudo apt-get install php5-ldap
* sudo apt-get install php5-curl
* sudo apt-get install php5-imap
* sudo apt-get install php5-imagick


**### Next step ###**

* sudo a2enmod rewrite
* sudo php5enmod imap
* sudo service apache2 restart

**### Setup virtual host ###**


```
#!php

<VirtualHost *:80>
        ServerAdmin webmaster@localhost
        ServerName univefservicedesk
        ServerAlias univefservicedesk.yourdomain.com
        DocumentRoot /var/www/univefservicedesk
        <Directory />
                Options FollowSymLinks
                AllowOverride All
        </Directory>
        <Directory /var/www/univefservicedesk/>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                Order allow,deny
                allow from all
        </Directory>

        ScriptAlias /cgi-bin/ /usr/lib/cgi-bin/
        <Directory "/usr/lib/cgi-bin">
                AllowOverride None
                Options +ExecCGI -MultiViews +SymLinksIfOwnerMatch
                Order allow,deny
                Allow from all
        </Directory>

        ErrorLog ${APACHE_LOG_DIR}/error.log

        # Possible values include: debug, info, notice, warn, error, crit,
        # alert, emerg.
        LogLevel warn

        CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```


**### Setup folders rights ###**

* sudo chown www-data:www-data -R protected/runtime/
* sudo chown www-data:www-data -R assets/
* sudo chown www-data:www-data -R protected/config/
* sudo chown www-data:www-data -R protected/data/
* sudo chown www-data:www-data -R media/
* sudo chown www-data:www-data -R uploads/
* sudo chown www-data:www-data -R protected/_backup/