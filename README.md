[![GitHub issues](https://img.shields.io/github/issues/tohidplus/paravel.svg)](https://github.com/tohidplus/paravel/issues)
[![GitHub stars](https://img.shields.io/github/stars/tohidplus/paravel.svg)](https://github.com/tohidplus/paravel/stargazers)
[![Total Downloads](https://img.shields.io/packagist/dt/tohidplus/paravel.svg)](https://packagist.org/packages/tohidplus/paravel)
[![GitHub license](https://img.shields.io/github/license/tohidplus/paravel.svg)](https://github.com/tohidplus/paravel/blob/master/LICENSE.txt)

# Paravel
A Laravel package to run anonymous functions in parallel
### Installation
```bash
composer require tohidplus/paravel
```
#### Laravel
Publish the config file 
```bash
php artisan vendor:publish --provider="Tohidplus\Paravel\ParavelServiceProvider"
```
#### Lumen
Copy the config file `paravel.php` from `vendor/tohidplus/paravel` to `config` directory and configure it in `bootstrap/app.php`
```bash
$app->configure('paravel');
//
$app->register(Tohidplus\Paravel\ParavelServiceProvider::class);
```
#### Configuration
```php
return [
    'artisan_path' => env('PARAVEL_ARTISAN_PATH', '/var/www/artisan'),
];
```
* Make sure the `artisan` path is correct.

### Basic example
```php
<?php
use Tohidplus\Paravel\Paravel;

$paravel = Paravel::create();

$time = microtime(true);        

$results = $paravel->add('label_1',function (){
    sleep(5);
    return 'Hello there';
})->add('label_2',function (){
     sleep(5);
     return 'Hello again';
})->wait();

//Check the total execution time
dump(microtime(true)-$time); // 5.* Secs
```
### Run functions in background
```php
<?php
use Tohidplus\Paravel\Paravel;

$paravel = Paravel::create();   
$paravel->add('label_1',function (){
    return 'Hello there';
})->add('label_2',function (){
     return 'Hello again';
})->run();
```
### Helper methods
```php
<?php
use Tohidplus\Paravel\Paravel;

$paravel = Paravel::create();   
$results = $paravel->add('label_1',function (){
    return 'Hello there';
})->add('label_2',function (){
     return 'Hello again';
})->wait();

// Get the item by label
$results->get('label_1');
// Get result of item
$results->resultOf('label_1');
// Get error of item
$results->errorOf('label_1');
// Get status of item
$results->statusOf('label_1');

// Check if all processes were succeeded.
$results->succeeded();
// Check if any of the processes was failed.
$results->failed();
```
