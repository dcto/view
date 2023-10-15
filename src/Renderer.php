<?php

/**
* 
* @package view
* @author  dc.To
* @version 20230827
* @copyright ©2023 dc team all rights reserved.
*/
namespace VM\View;

/**
 * Dynamic call method
 * @method config(string $key, mixed $value)
 * @method assign(...$values)
 * @method render(string $template, array $data = [])
 * @method path(...$paths)
 * @method addPath(string $path)
 * @method getPath()
 * @method getEngine($new = false)
 * @method setEngine($engine)
 * @return mixed
 */
 abstract class Renderer {

    /**
     * assign variable.
     * @var array
     */
    protected $assign = [];

    /**
     * view cache
     */
    protected $config = [];

    /**
     * Property paths.
     *
     * @var  \SplPriorityQueue
     */
    protected $paths;

    /**
     * Property engine.
     *
     * @var \Twig\Environment|\League\Plates\Engine|Illuminate\View\Factory|\Latte\Engine|\Mustache_Engine
     */
    protected $engine;
    
    /**
     * Renderer
     */
    protected $renderer;

    /**
     * Class init.
     *
     * @param Renderer $renderer
     * @param array             $config
     */
    public function __construct()
    {
        $this->paths = new \SplPriorityQueue();
        $this->paths->insert(app_dir('View'), 100);
        $this->config(['cache'=>runtime('view', _APP_)]);
    }

     /**
	 * set layout assign
	 * @param mixed $values
	 */
	public function assign(...$values) 
    {
        if(count($values)==2 && is_string($values[0])){
            $this->assign[$values[0]] = $values[1];
        }else{
            $this->assign = array_merge($this->assign, ...array_map(function($value){
                if ($value instanceof \Traversable) {
                    return iterator_to_array($value);
                }else if (is_object($value)) {
                    return get_object_vars($value);
                }else{
                    return $value;
                }
        }, $values));
        }
        
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
            $this->getEngine(true);
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
            $this->paths->insert($path, 100 - ($i++ * 10));
        }, $paths);
        $this->getEngine(true);
        return $this;
	}

    /**
     * finFile
     * @param string $file
     * @return  string
     */
    protected function load($file)
    {
        return $file;
        //throw new \UnexpectedValueException(sprintf('File: %s not found. in paths queue: %s', $file, join(',',$this->getPath()) ));
    }

    /**
     * getPath
     * @return array|string
     */
    public function getPath($method = null)
    {
        return $method ? $this->paths->$method() : iterator_to_array($this->paths);
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
        $this->getEngine(true);
        return $this;
    }

    /**
     * render method
     * @param string $file
     * @param array $data
     */
    abstract public function render($file, ...$data);

    /**
     * Method getEngine
     * @param bool $new
     */ 
    abstract public function getEngine($new = false);

    /**
     * Method getEngine
     * @param bool $new
     */ 
    abstract public function setEngine(mixed $engine);
}