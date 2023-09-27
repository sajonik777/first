#!/usr/bin/env bash
apt-get update
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get -y upgrade
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y git
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y mc
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y zip
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php-common
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php-curl
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php-imap
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php-ldap
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php-gd
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php-imagick
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php-mcrypt
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php-mbstring
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php-mysql
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php-mysqli
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php-xml
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php-zip
echo "СЛЕДУЮЩИЙ ШАГ!"
sudo apt-get install -y apache2
echo "СЛЕДУЮЩИЙ ШАГ!"
sudo apt-get install -y libapache2-mod-php
echo "СЛЕДУЮЩИЙ ШАГ!"
sudo apt-get install -y mysql-server
echo "СЛЕДУЮЩИЙ ШАГ!"
sudo apt-get install -y mysql-client
echo "СЛЕДУЮЩИЙ ШАГ!"
sudo a2enmod rewrite
echo "СЛЕДУЮЩИЙ ШАГ!"
sudo service apache2 restart
echo "СЛЕДУЮЩИЙ ШАГ!"
sudo cp -f /var/www/univefservicedesk/lib/000-default.conf /etc/apache2/sites-available/000-default.conf
echo "СЛЕДУЮЩИЙ ШАГ!"
sudo cp -f /var/www/univefservicedesk/lib/ioncube_loader_lin_7.0.so /usr/lib/php/ioncube_loader_lin_7.0.so
echo "СЛЕДУЮЩИЙ ШАГ!"
sudo cp -f /var/www/univefservicedesk/lib/mysqld.cnf /etc/mysql/mysql.conf.d/mysqld.cnf
echo "СЛЕДУЮЩИЙ ШАГ!"
sudo cp -f /var/www/univefservicedesk/lib/php.ini /etc/php/7.0/apache2/php.ini
sudo cp -f /var/www/univefservicedesk/lib/php.ini /etc/php/7.0/cli/php.ini
sudo cp -f /var/www/univefservicedesk/lib/www-data /var/spool/cron/crontabs/www-data
chown www-data:www-data -R /var/www/
chown www-data:www-data /var/spool/cron/crontabs/www-data
echo "СЛЕДУЮЩИЙ ШАГ!"
sudo service apache2 restart
sudo service mysql restart
echo "СЛЕДУЮЩИЙ ШАГ!"
echo "Введите пароль ROOT от MySQL, которого указывали при установке MySQL"
sudo mysqladmin -u root -p create univefservicedesk
echo "СЛЕДУЮЩИЙ ШАГ!"
echo "ЗАВИСИМОСТИ УСПЕШНО УСТАНОВЛЕНЫ! ПЕРЕЙДИТЕ В ВЕБ-ИНТЕРФЕЙС ПО IP ДЛЯ ПРОДОЛЖЕНИЯ УСТАНОВКИ."
