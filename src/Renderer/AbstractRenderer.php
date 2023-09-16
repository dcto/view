<?php

/**
* 
* @package view
* @author  dc.To
* @version 20230827
* @copyright ©2023 dc team all rights reserved.
*/
namespace VM\View\Renderer;

/**
 * Class AbstractRenderer
 *
 *
 * @since 2.0
 */
abstract class AbstractRenderer 
{
    /**
     * assign variable.
     */
    protected $assign = [
        '_'=>null,
        '_VM_'=>_VM_,
        '_APP_'=>_APP_,
    ];

    /**
     * Property config.
     *
     * @var \ArrayAccess
     */
    protected $config = [
        'cache'=>_DOC_._DS_.'runtime'._DS_.'view'._DS_._APP_
    ];


    /**
     * Property paths.
     *
     * @var  \SplPriorityQueue
     */
    protected $paths = [];


    /**
     * Property engine.
     *
     * @var self
     */
    protected $engine = null;

    /**
     * Class init.
     *
     * @param \SplPriorityQueue $paths
     * @param array             $config
     */
    public function __construct($config = [])
    {
        $this->config['_'] = make('lang');
        $this->config($config);
        $this->paths = new \SplPriorityQueue();
        $this->path(_DIR_._DS_.'View');
    }

    /**
	 * set layout assign
	 * @param mixed $values
	 */
	public function assign(...$values) {
        if(!$values) return $this->assign;
        array_map(function($value){
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
	public function config($item, $default = null) {
        if(is_array($item)){
            $this->config = array_merge($this->config, $item);
            return $this;
        }else if(is_string($item)){
            return isset($this->config[$item]) ? $this->config[$item] : $default;
        }
        return $this;
	}

	/**
	 * get engine of view
	 */
	public function engine() {
		return $this->engine;
	}
	
    /**
     * Escape the output.
     */
	public function escape($output)
	{
		return htmlspecialchars($output, ENT_COMPAT, 'UTF-8');
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
     * Method to get property Engine
     *
     * @param   boolean $new
     *
     * @return  object
     */
    abstract public function getEngine($new = false);

    /**
     * Method to set property engine
     *
     * @param   object $engine
     *
     * @return  static  Return self to support chaining.
     */
    abstract public function setEngine($engine);

    /**
     * finFile
     *
     * @param string $file
     * @param string $ext
     *
     * @return  string
     */
    public function findFile($file, $ext = '')
    {
        $paths = $this->getPath();
        $file = str_replace('.', '/', $file);
        $ext = $ext ? '.' . trim($ext, '.') : '';
        foreach ($paths as $path) {
            $filePath = $path . '/' . $file . $ext;
            if (is_file($filePath)) {
                return realpath($filePath);
            }
        }
        return null;
    }

    /**
     * @param string $file
     * @param string $ext
     * @return  bool
     */
    public function exist(string $file, string $ext = ''): bool
    {
        return $this->findFile($file, $ext) !== null;
    }

    /**
     * getPath
     * @return array
     */
    public function getPath()
    {
        $paths = clone $this->paths;
        $items = [];
        foreach($paths as $path){
            $items[] = $path;
        }
        return $items;
    }

    /**
     * addPath
     *
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
    * @param  string $file
    * @param  array $data 
    * @return void 
    */
    abstract public function render($file, $data = []);
}
