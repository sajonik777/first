apt-get update
locale-gen en_US.UTF-8
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y software-properties-common
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y git
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y curl
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y zip
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y bzip2
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php7.0
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php7.0-curl
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php7.0-imap
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php7.0-ldap
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php7.0-gd
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php7.0-imagick
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php7.0-mcrypt
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php7.0-mbstring
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php7.0-mysql
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php7.0-mysqli
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php7.0-xml
echo "СЛЕДУЮЩИЙ ШАГ!"
apt-get install -y php7.0-zip
echo "СЛЕДУЮЩИЙ ШАГ!"
sudo apt-get install -y mysql-server
echo "СЛЕДУЮЩИЙ ШАГ!"
sudo apt-get install -y mysql-client
echo "ТЕКУЩИЙ ПАРОЛЬ ROOT ОТСУТСТВУЕТ! СОЗДАЙТЕ НОВЫЙ ПАРОЛЬ ДЛЯ ROOT И НА ВСЕ ВОПРОСЫ ОТВЕТ YES"
sudo mysql_secure_installation
echo "СЛЕДУЮЩИЙ ШАГ! СОЗДАЕМ БД......."
sudo mysqladmin -u root create univefservicedesk
echo "ЗАДАЙТЕ И ЗАПОМНИТЕ ПАРОЛЬ ПОЛЬЗОВАТЕЛЯ UNIVEF SERVICE DESK ДЛЯ ПОДКЛЮЧЕНИЯ К БД:"
read dbpass
mysql -u root -e "CREATE USER 'univefservicedesk'@'localhost' IDENTIFIED BY '$dbpass';"
mysql -u root -e "GRANT ALL PRIVILEGES ON * . * TO 'univefservicedesk'@'localhost';"
mysql -u root -e "FLUSH PRIVILEGES;"
echo "СЛЕДУЮЩИЙ ШАГ!"
sudo a2enmod rewrite
echo "СЛЕДУЮЩИЙ ШАГ!"
sudo systemctl restart apache2
echo "СЛЕДУЮЩИЙ ШАГ!"
sudo cp -f /var/www/univefservicedesk/lib/000-default.conf /etc/apache2/sites-available/000-default.conf
echo "СЛЕДУЮЩИЙ ШАГ!"
sudo cp -f /var/www/univefservicedesk/lib/ioncube_loader_lin_7.0.so /usr/lib/php/ioncube_loader_lin_7.0.so
echo "СЛЕДУЮЩИЙ ШАГ!"
sudo cp -f /var/www/univefservicedesk/lib/php.ini /etc/php/7.0/apache2/php.ini
sudo cp -f /var/www/univefservicedesk/lib/php.ini /etc/php/7.0/cli/php.ini
sudo cp -f /var/www/univefservicedesk/lib/www-data /var/spool/cron/crontabs/www-data
sudo chown www-data:www-data -R /var/www/
sudo chown www-data:www-data -R /var/spool/cron/
echo "СЛЕДУЮЩИЙ ШАГ!"
sudo systemctl restart apache2
sudo systemctl restart mariadb
echo "ЗАВИСИМОСТИ УСПЕШНО УСТАНОВЛЕНЫ! ПЕРЕЙДИТЕ В ВЕБ-ИНТЕРФЕЙС ПО IP ДЛЯ ПРОДОЛЖЕНИЯ УСТАНОВКИ."
