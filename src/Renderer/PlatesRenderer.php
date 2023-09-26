<?php 
/**
 * @package \VM\View\Renderer
 */
namespace VM\View\Renderer;


use League\Plates\Engine as PlatesEngine;
use League\Plates\Extension\ExtensionInterface;

/**
 * The PlatesRenderer class.
 *
 * @since  2.0
 */
class PlatesRenderer
{
    /**
     * Property extensions.
     *
     * @var  ExtensionInterface[]
     */
    protected $extension = '.tpl';

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
            $this->engine = new PlatesEngine($this->getPath()[0]);
            $this->engine->loadExtension($this->extension);
        }
        return $this->engine;
    }

    /**
     * Method to set property engine
     *
     * @param   PlatesEngine $engine
     *
     * @return  static  Return self to support chaining.
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
    public function render($file, $data = [])
    {    
        return $this->getEngine()->render($this->findFile($file), $this->assign($data)->assign);
    }

    /**
     * addFolder
     *
     * @param   string  $namespace
     * @param   string  $folder
     * @param   boolean $fallback
     *
     * @return  static
     */
    protected function addFolder($namespace, $folder, $fallback = false)
    {
        $this->paths[$namespace] = [
            'folder' => $folder,
            'fallback' => $fallback,
        ];

        return $this;
    }
}
