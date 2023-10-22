#  Varimax view module

### Supported Template Engine

- Php (Native php engine template)
- Twig https://twig.symfony.com/doc
- Blade https://laravel.com/docs/8.x/blade
- Latte https://latte.nette.org
- Plates https://platesphp.com
- Mustache https://mustache.github.io



### Installation

##### Step.1 
```
composer require varimax/view 
```

##### Choice your template engine
```
composer require latte/latte
composer require league/plates
composer require illuminate/view
composer require twig/twig
composer require mustache/mustache
```


##### Step.2
Add the following service config to your `config.php` file:
```php
'service' => [
   // \VM\View\ViewServiceProvider::Blade()  //Blade Template Engine
   // \VM\View\ViewServiceProvider::Latte()  //Latte Template Engine
  //  \VM\View\ViewServiceProvider::Plates() //Plates Template Engine
    \VM\View\ViewServiceProvider::Twig()    //Twig Template Engine
    // \VM\View\ViewServiceProvider::Mustache()    //Mustache Template Engine
]
```


##### Step.3

add the following code to your `controller`:

```php
$data1 = ['test'=>'test'];
$data2 = ['test'=>'test2'];

make('view')->render('template.twig', $data1, $data2);
```


### The varimax view service methods
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