<?php 

/**
 * @package \VM\View\Renderer
 */

namespace VM\View\Renderer;

use VM\View\Renderer;


class MustacheRenderer extends Renderer
{
    /**
     * Method to get property Engine
     *
     * @param boolean $new
     *
     * @return \Latte\Engine
     */
    public function getEngine($new = false)
    {
        if (!$this->engine || $new) {
            $this->config(['loader'=> new \Mustache_Loader_FilesystemLoader($this->getPath('current'))]);

            foreach($this->getPath() as $path){
                $config['partials_loader'] = new \Mustache_Loader_FilesystemLoader($path);
            }
            $this->engine = new \Mustache_Engine($this->config);
        }
        return $this->engine;
    }

    /**
     * Method to set property engine
     * @param \Mustache_Engine $engine
     * @return static  Return self to support chaining.
     */
    public function setEngine($engine)
    {
        if (!($engine instanceof \Mustache_Engine)) {
            throw new \InvalidArgumentException('Invalid Engine '. __CLASS__);
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
        return $this->getEngine()->render($this->getPath('current')._DS_.$this->load($file), $this->assign(...$data)->assign);
    }
}
