<?php 

/**
 * @package \VM\View\Renderer
 */

namespace VM\View\Renderer;

use VM\View\Renderer;

class LatteRenderer extends Renderer
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
            $this->engine = new \Latte\Engine;
            $this->engine->setLoader(new \Latte\Loaders\FileLoader($this->getPath('current')));
            $this->engine->setTempDirectory($this->cache())->setStrictTypes(false);
            
            $this->engine::VERSION > 3.0 && $this->engine->addExtension(new \Latte\Essential\RawPhpExtension);
        }
        return $this->engine;
    }

    /**
     * Method to set property engine
     * @param \Latte\Engine $engine
     * @return static  Return self to support chaining.
     */
    public function setEngine($engine)
    {
        if (!($engine instanceof \Latte\Engine)) {
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
        return $this->getEngine()->renderToString($this->load($file), $this->assign(...$data)->assign);
    }
}
