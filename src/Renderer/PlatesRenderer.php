<?php 

/**
 * @package \VM\View\Renderer
 */

namespace VM\View\Renderer;

use VM\View\Renderer;
use League\Plates\Engine as PlatesEngine;
use League\Plates\Extension\ExtensionInterface;

/**
 * The PlatesRenderer class.
 * @since  2.0
 */
class PlatesRenderer extends Renderer
{
    /**
     * Method to get property Engine
     *
     * @param   boolean $new
     *
     * @return  PlatesEngine
     */
    public function getEngine($new = false)
    {
        if (!$this->engine || $new) {
            $paths = $this->getPath();
            $this->engine = new PlatesEngine(array_shift($paths));
            array_map(function($path){
                $this->engine->addFolder($path, $path);
            }, $paths);
        }
        return $this->engine;
    }

    /**
     * Method to set property engine
     * @param PlatesEngine $engine
     * @return static  Return self to support chaining.
     */
    public function setEngine($engine)
    {
        if (!($engine instanceof PlatesEngine)) {
            throw new \InvalidArgumentException('Engine object should be Mustache_Engine');
        }
        $this->engine = $engine;
        return $this;
    }

    /**
     * render
     *
     * @param string $file
     * @param array  $data
     *
     * @return  string
     */
    public function render($file, ...$data)
    {    
        return $this->getEngine()->render($this->load($file), $this->assign(...$data)->assign);
    }
}
