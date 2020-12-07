in the project folder :
composer install

Please fill .env with your infos
Create database and tables : 
php bin/console doctrine:database:create

php bin/console doctrine:migrations:migrate

Load sample data :
php bin/console doctrine:fixtures:load

to start Symfony Local Web Server :
symfony server:start
