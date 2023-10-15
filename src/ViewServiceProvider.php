<?php

/**
* 
* @package view
* @author  dc.To
* @version 20230827
* @copyright ©2023 dc team all rights reserved.
*/
namespace VM\View;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

/**
 * Set View Engine
 * @method static self Php() Using Php View Engine
 * @method static self Blade() Using Blade View Engine
 * @method static self Mustache() Using Mustache View Engine
 * @method static self Latte() Using Latte View Engine
 * @method static self Plates() Using Plates View Engine
 * @method static self Twig() Using Twig View Engine
 */
class ViewServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * The View Engine Renderer
     */
    static $view;

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;
    
	/**
     * Register view service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('view',__NAMESPACE__.static::$view);
    }

    public static function __callStatic($view, $arguments)
    {
        static::$view = sprintf("\Renderer\%sRenderer", $view);
        return static::class;
    }
}