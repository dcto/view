#  Varimax view module

### Supported Template Engine

- Php (Native php engine template)
- Twig https://twig.symfony.com/doc/2.x/
- Blade https://laravel.com/docs/8.x/blade
- Plates https://platesphp.com/


### Installation

##### Step.1 
```
composer require varimax/varimax
composer install varimax/view 
```

The varimax view service methods
```php

make('view')->config(string $key, mixed $value)
make('view')->path(...$paths)
make('view')->addPath(string $path)
make('view')->getPath()
make('view')->getEngine($new = false)
make('view')->setEngine($engine)
make('view')->assign(...$values)
make('view')->render(string $template, array ...$values)

```


##### Step.2
Add the following service config to your `config.php` file:
```php
'service' => [
   // \VM\View\ViewServiceProvider::Blade()  //Blade Template Engine
  //  \VM\View\ViewServiceProvider::Plates() //Plates Template Engine
    \VM\View\ViewServiceProvider::Twig()    //Twig Template Engine
]
```


##### Step.3

add the following code to your `controller`:

```php
$data1 = ['test'=>'test'];
$data2 = ['test'=>'test2'];

make('view')->render('template.html', $data1, $data2);
```