#Laravel API
Just skelton to start api application with laravel

## Requirements
- PHP version: 7.2
- Node version: v8.11.1
- npm version: 5.6.0

Apache rewrite module must be enable, PDO, Mysql extensions must be installed and enabled.

You can check libraries detail in your composer.json file.
### Reference links:
 - https://laravel.com/docs/5.6/authentication#included-authenticating [Auth]
 - https://laravel.com/docs/5.6/passport#deploying-passport [Token]
 - http://laratrust.readthedocs.io/en/5.0/usage/concepts.html [ACL]

## Installation

Just clone the project in in your www or htdocs directory.

Go into project folder
Then you can install all dependencies via Composer by running this command:
```bash
composer install

```
Composer detail:
https://getcomposer.org/

```bash
php artisan migrate

php artisan passport:install --force

php artisan db:seed

```

## Setup Database
 
Then modify .env file with database name, user and password.

Then run below all commands:

```bash
php artisan config:clear
php artisan cache:clear 
php artisan view:clear
php artisan key:generate
```
 
## API detail
Just check route.php for api routes.
 
