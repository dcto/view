<?php

/**
* 
* @package view
* @author  dc.To
* @version 20230827
* @copyright ©2023 dc team all rights reserved.
*/
namespace VM\View;


class Renderer {

    /**
     * 
     * @var VM\View\Renderer
     */
    protected $renderer;


    /**
     * Class init.
     *
     * @param Renderer\AbstractRenderer $renderer
     * @param array             $config
     */
    public function __construct($render = null)
    {
        $render && $this->make($render);
    }

    /**
     * make renderer
     * @param string $renderer
     */
    public function make($render){
        $render = sprintf(__NAMESPACE__.'\\Renderer\\%sRenderer', ucfirst($render));
        $this->renderer = new $render;
        return $this;
    }

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
    public function __call($name, $arguments)
    {
        if (! method_exists($this->renderer, $name)) {
            throw new \BadMethodCallException(sprintf('Call to undefined method %s::%s()', get_class($this), $name));
        }
        return $this->renderer->{$name}(...$arguments);
    }
}