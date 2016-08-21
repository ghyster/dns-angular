# dns-angular

##Presentation
This project is a web interface created to manage DNS records easily, providing you have enabled TSIG updates on your DNS server.
It's using Laravel as backend, angularjs and angular-material as frontend

##Prerequisites
- A web server (apache or nginx)
- A database server (MySQL or other servers supported by laravel) with a database for the application
- Laravel requirements https://laravel.com/docs/5.2/installation#server-requirements
- Currently there's only 2 authentication modes available : [google sign in](https://developers.google.com/+/web/signin/) or SAMLv2. You either need a google client id or a SAMLv2 iDP

##Installation

- clone this repository to your web folder :
```
git clone https://github.com/ghyster/dns-angular.git *your_web_folder*
```
- in your web folder run composer update to get all the dependencies :
```
composer update
```
- copy the file .env.example to .env (it will contain your settings) :
```
cp .env.example .env
```
- generate you application key using artisan :
```
php artisan key:generate
```
- check your webserver has write permissions on the storage folder and on the .env file and then browse to your website to begin the configuration
