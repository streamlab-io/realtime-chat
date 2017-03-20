## Download the files 

download all files

## Requirements

PHP >= 5.6.4 , 
PHP Curl extension 

## Install The Dependencies

now type these lines on your console

```
composer install
```
```
php artisan key:generate
```

## migrate

```
  php artisan migrate
```

## Add App key 

go to streamlab website http://streamlab.io/
then open application get the `key` and `token` add them to `config/stream_lab.php`

```php
  return[
      'app_id'=>'',
      'token'=>''
  ];
```

## Create Cahnnel

make sure you create public `channel` to your application call `public`


## Start

```
 php artisan serve
```

and enjoy.

