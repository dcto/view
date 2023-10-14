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
 * Class View
 * @since 2.0
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

    /**
     * Set View Engine
     */
    public static function __callStatic($view, $arguments)
    {
        static::$view = sprintf("\Renderer\%sRenderer", $view);
        return static::class;
    }
}