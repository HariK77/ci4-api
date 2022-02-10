# Ci4 Rest Api

This is simple rest api, made with [CodeIgniter4](http://codeigniter.com) framework. It has jwt authentication, crud functionality etc.,

More information about [CodeIgniter 4] framework can be found [here](https://codeigniter4.github.io/userguide/).

## Server Requirements

PHP version 7.3 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php)
- xml (enabled by default - don't turn it off)

If any issues instllation visit this page 
- Make sure you have met all the server requirements mentioned [here](https://codeigniter.com/user_guide/intro/requirements.html).

## Installation instructions

- Clone the repository with, $ `git clone https://github.com/HariK77/ci4-api.git`.  
- run `$ composer install`.
- run `$ sudo chmod -R 0777 writable/` (no need for windows xampp). 
- create a .env file, run `$ cp env .env` (In windows just rename the file manually).
- Uncomment CI_ENVIRONMENT line in .env and make it 'development' instead of production.
- configure db connections and base url in .env (change base url in App.php also)
- create database and run `$ php spark migrate`.
- create dummy data in user table with `$ php spark db:seed UserSeeder`.
- Test it using http://localhost/ci4-api/public.

## How to use the application

- Import the postman collection (`Ci4 Api.postman_collection.json`) that has been added in the repository.

That's is u can start testing the `ci4-api`

