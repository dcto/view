<?php 

/**
* 
* @package view
* @author  dc.To
* @version 20230827
* @copyright ©2023 dc team all rights reserved.
*/
namespace VM\View\Renderer;


use League\Plates\Engine as PlatesEngine;
use League\Plates\Extension\ExtensionInterface;

/**
 * The PlatesRenderer class.
 *
 * @since  2.0
 */
class PlatesRenderer extends AbstractRenderer
{
    /**
     * Property extensions.
     *
     * @var  ExtensionInterface[]
     */
    protected $extensions = [];

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
            $this->engine = new PlatesEngine(
                $this->getPath()[0]
            );
            foreach ($this->paths as $namespace => $folder) {
                $this->engine->addFolder($namespace, $folder['folder'], $folder['fallback']);
            }

            foreach ($this->extensions as $extension) {
                $this->engine->loadExtension($extension);
            }
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
        $this->assign($data);        
        return $this->getEngine()->render($this->findFile($file), $this->assign);
    }

    /**
     * findFile
     *
     * @param string $file
     * @param string $ext
     *
     * @return  string
     */
    public function findFile($file, $ext = '')
    {
        $ext = $ext ?: $this->config('extension', 'tpl');

        return parent::findFile($file, $ext);
    }

    /**
     * addExtension
     *
     * @param ExtensionInterface $extension
     *
     * @return  static
     */
    public function addExtension(ExtensionInterface $extension)
    {
        $this->extensions[] = $extension;

        return $this;
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
    public function addFolder($namespace, $folder, $fallback = false)
    {
        $this->paths[$namespace] = [
            'folder' => $folder,
            'fallback' => $fallback,
        ];

        return $this;
    }
}
