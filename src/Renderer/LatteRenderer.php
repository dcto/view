<?php 

/**
 * @package \VM\View\Renderer
 */

namespace VM\View\Renderer;

use VM\View\Renderer;

/**
 * The PlatesRenderer class.
 * @since  2.0
 */
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
            $this->engine->setTempDirectory($this->config('cache'))->setStrictTypes(false);
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
        return $this->getEngine()->renderToString($this->getPath('current')._DS_.$this->load($file), $this->assign(...$data)->assign);
    }
}
