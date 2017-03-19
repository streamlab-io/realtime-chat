## Download the files 

download all files

## Requirements

PHP >= 5.6.4 , 
PHP Curl extension 

## Install  The Dependencies

now type this line on your console

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

make sure you create public `channels` to your application


## Last Thing

go to this path /vendor/5dmatweb/streamlab/src/routes/streamlabRoutes.php
change line number 13  to this

```php
  return \StreamLab\StreamLabProvider\Facades\StreamLabFacades::pushMessage($request->channelName , $request->eventName , $request->message);
```

## Start

```
 php artisan serve
```

and enjoy

