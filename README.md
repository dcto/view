#  Varimax view module

### Supported Template Engine

- Php (Native php engine template)
- Twig https://twig.symfony.com/doc/2.x/
- Blade https://laravel.com/docs/8.x/blade
- Plates https://platesphp.com/


### Installation

##### Step.1 
```
composer install varimax/view 
```

##### Step.2
Add the following providers code to your `config.php` file:
```php
<?php
    'view'=>[
        'driver' => 'php', //php|blade|plates|twig
        #debug模式
        'debug' => getenv('DEBUG') ? true : false,

        #缓存路径 false|path
        'cache' => runtime('view', _APP_),

        #reload重新编译
        'reload' => getenv('DEBUG') ? true : false,

        #视图文件后缀
        'append'  => 'twig',
        
        #自动转义
        'autoescape'=>true,
    ],


    'providers' => [
        \VM\View\ViewServiceProvider::class
    ]
?>
```


##### Step.3

add the following code to your `controller`:

```php
make('view')->blade($TEMPLATE_DIR)->config(array $CONFIG);
```