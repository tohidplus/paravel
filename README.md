[![GitHub issues](https://img.shields.io/github/issues/tohidplus/paravel.svg)](https://github.com/tohidplus/paravel/issues)
[![GitHub stars](https://img.shields.io/github/stars/tohidplus/paravel.svg)](https://github.com/tohidplus/paravel/stargazers)
[![Total Downloads](https://img.shields.io/packagist/dt/tohidplus/paravel.svg)](https://packagist.org/packages/tohidplus/paravel)
[![GitHub license](https://img.shields.io/github/license/tohidplus/paravel.svg)](https://github.com/tohidplus/mellat/blob/master/LICENSE.txt)

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
> Before installation make sure you've installed `illuminate/redis` according the [Lumen documentation](https://lumen.laravel.com/docs/7.x/cache).

Copy the config file `paravel.php` from `vendor/tohidplus/paravel` to `config` directory and configure it in `bootstrap/app.php`
```bash
$app->configure('paravel');
```
#### Configuration
```php
return [
    'artisan_path' => env('PARAVEL_ARTISAN_PATH', '/var/www/artisan'),
    'redis_connection' => env('PARAVEL_REDIS_CONNECTION', 'default'),
    'waiting_timeout' => env('PARAVEL_WAITING_TIMEOUT', 30)
];
```
* Make sure the `artisan` path is correct.
* If you want to change the redis connection you have to add a new connection in `config/database.php` file at `redis` section.
* Waiting timeout can dynamically be changed as you need.
```php
<?php
use Tohidplus\Paravel\Paravel;
$paravel = Paravel::create();
$paravel->timeout(60);
```
### Usage
```php
<?php

namespace App\Http\Controllers;
use Tohidplus\Paravel\Paravel;
class SomeController extends Controller{
   
    public function index(){
        $paravel = Paravel::create();
        
        $time = microtime(true);        

        // Result is an instance of Collection   
        $result = $paravel->add('label_1',function (){
            sleep(5);
            return 'Hello there';
        })->add('label_2',function (){
             sleep(5);
             return 'Hello there';
        })->run();

        //Check the total execution time
        dump(microtime(true)-$time); // 5.* Secs

        $label_1 = $result->where('label','label_1')->first();
        dump($label_1['status']); // If any exception was occurred, this will be false
        dump($label_1['result']);
        dump($label_1['error']); 
    }
}
```
