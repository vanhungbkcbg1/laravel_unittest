# blank
laravel application 

## prepare for unit test
#### step 1 execute to mysql container and execute command below
````
mysql -u root -p
// enter password and then run command

create database db_test;
````

#### step 2 execute to php container and run command to generate schema for unit test
````
php artisan migrate --env=testing
````
