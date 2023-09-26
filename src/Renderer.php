<?php

/**
* 
* @package view
* @author  dc.To
* @version 20230827
* @copyright ©2023 dc team all rights reserved.
*/
namespace VM\View;

use Illuminate\Support\Traits\Macroable;

/**
 * Dynamic call method
 * @method config(string $key, mixed $value)
 * @method assign(string $key, mixed $value)
 * @method render(string $template, array $data = [])
 * @method escape()
 * @method path(...$paths)
 * @method addPath(string $path)
 * @method getPath()
 * @method exist(string $file)
 * @method getEngine()
 * @method setEngine($engine)
 * @return mixed
 */
class Renderer {

    use Macroable;

    /**
     * assign variable.
     */
    protected $assign;

    /**
     * view config
     */
    protected $config = [
        'cache'=>_DOC_._DS_.'runtime'._DS_.'view'._DS_._APP_
    ];

    /**
     * Property paths.
     *
     * @var  \SplPriorityQueue
     */
    protected $paths;

    /**
     * Property engine.
     *
     * @var \Twig\Environment|\League\Plates\Engine|Illuminate\View\Factory
     */
    protected $engine;
    
    /**
     * Renderer
     */
    protected $renderer;

    /**
     * extensions.
     * @var string
     */
    protected $extension;

    /**
     * Class init.
     *
     * @param Renderer\AbstractRenderer $renderer
     * @param array             $config
     */
    public function __construct($render = null)
    {
        $render && $this->make($render);
        $this->paths = new \SplPriorityQueue();
        $this->path(_DIR_._DS_.'View');
    }

     /**
	 * set layout assign
	 * @param mixed $values
	 */
	public function assign(...$values) 
    {
        $values  && array_map(function($value){
            if ($value instanceof \Traversable) {
                $value = iterator_to_array($value);
            }else if (is_object($value)) {
                $value = get_object_vars($value);
            }
            $this->assign = array_merge($this->assign, $value);
        }, $values);
		return $this;
	}

	/**
	 * set engine config
	 * @param array|string config $item
	 */
	public function config($item = null, $default = null) {
        if(is_string($item)){
            return isset($this->config[$item]) ? $this->config[$item] : $default;
        }else if(is_array($item)){
            $this->config = array_merge($this->config, $item);
        }
        return $this;
	}

    /**
	 * add base path
	 * @param string $path
	 * @param int    $priority
	 * @return  self
	 */
	public function path(...$paths)
	{
        $i = 1;
        array_map(function($path)use(&$i){
            $this->addPath($path, 100 - ($i++ * 10));
        }, $paths);
        return $this;
	}

    /**
     * finFile
     * @param string $file
     * @return  string
     */
    protected function findFile($file)
    {
        if(pathinfo($file, PATHINFO_EXTENSION) != $this->extension){
            $file = str_replace('.', _DS_, $file).trim($file, '.'). $this->extension;
        }
        return array_map(function($path) use($file){
            if(is_file($path._DS_.$file)) return realpath($path._DS_.$file);
        }, $this->getPath());
    }

    /**
     * @param string $file
     * @param string $ext
     * @return  bool
     */
    protected function exist(string $file, string $ext = ''): bool
    {
        return $this->findFile($file, $ext) !== null;
    }

    /**
     * getPath
     * @return array
     */
    public function getPath()
    {
        return iterator_to_array($this->paths);
    }

    /**
     * addPath
     * @param string  $path
     * @param integer $priority
     * @return  static
     */
    public function addPath($path, $priority = 100)
    {
        $this->paths->insert($path, $priority);
        return $this;
    }


    /**
     * make renderer
     * @param string $renderer
     */
    public function make($render, $config = [], $path = []){
        if(!$this->renderer){
            $this->mixin(new (sprintf(__NAMESPACE__.'\\Renderer\\%sRenderer', ucfirst($render))));
        }
        $this->path($path)->config($config);
        return $this;
    }

    /**
     * @param array $config
     * @param string $path
     * @return VM\View\Renderer\PhpRenderer
     */
    public function php(array $config = [], $path = null){
        return $this->make(__FUNCTION__, $config, $path);
    }

    /**
     * @param array $config
     * @param string $path
     * @return VM\View\Renderer\BladeRenderer
     */
    public function blade(array $config = [], $path = null){
        return $this->make(__FUNCTION__, $config, $path);
    }

    /**
     * @param array $config
     * @param string $path
     * @return VM\View\Renderer\PlatesRenderer
     */
    public function plates(array $config = [], $path = null){
        return $this->make(__FUNCTION__, $config, $path);
    }

    /**
     * @param array $config
     * @param string $path
     * @return VM\View\Renderer\TwigRenderer
     */
    public function twig(array $config = [], $path = null){
        return $this->make(__FUNCTION__, $config, $path);
    }


    // public function __call($name, $arguments)
    // {
    //     if (! method_exists($this->renderer, $name)) {
    //         throw new \BadMethodCallException(sprintf('Call to undefined method %s::%s()', get_class($this), $name));
    //     }
    //     return $this->renderer->{$name}(...$arguments);
    // }
}