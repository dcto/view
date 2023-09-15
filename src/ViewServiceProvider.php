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
     * Register view service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->booting(function(){

        });
        $name = 'php';
        $this->app->singleton('view', sprintf(__NAMESPACE__.'\\Renderer\\%sRenderer', ucfirst($name)));
    }
}