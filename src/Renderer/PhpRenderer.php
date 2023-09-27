<?php 
/**
 * @package \VM\View\Renderer
 */
namespace VM\View\Renderer;

/**
 * @since 2.0
 */
class PhpRenderer
{
    /**
     * Property file.
     *
     * @var string
     */
    protected $file;

    /**
     * Property block.
     *
     * @var  array
     */
    protected $block = [];

    /**
     * Property blockQueue.
     *
     * @var  \SplQueue
     */
    protected $blockQueue = null;

    /**
     * Property currentBlock.
     *
     * @var  string
     */
    protected $currentBlock = null;

    /**
     * Property extends.
     *
     * @var  string
     */
    protected $extend = null;

    /**
     * Property parent.
     *
     * @var  PhpRenderer
     */
    protected $parent = null;

    /** @var string */
    protected $extension = 'php';
    
    /**
     * render
     *
     * @param string $file
     * @param array  $__data
     *
     * @throws  \UnexpectedValueException
     * @return  string
     */
    public function render($file, $__data = [])
    {
        $__filePath = $this->findFile($file);
        if (!$__filePath) {
            $__paths = $this->getPath();
            $__paths = "\n " . implode(" |\n ", $__paths);
            throw new \UnexpectedValueException(sprintf('File: %s not found. Paths in queue: %s', $file, $__paths));
        }

        foreach ($__data as $key => $value) {
            if ($key === 'data') {
                $key = '_data';
            }

            $$key = $value;
        }

        unset($__data);

        // Start an output buffer.
        ob_start();

        // Load the layout.
        include $__filePath;

        // Get the layout contents.
        $output = ob_get_clean();

        // Handler extend
        if (!$this->extend) {
            return $output;
        }

        /** @var $parent phpRenderer */
        $parent = $this->createSelf();

        foreach ($this->block as $name => $block) {
            $parent->setBlock($name, $block);
        }

        $output = $parent->render($this->extend, $this->assign($__data)->assign);

        return $output;
    }

    /**
     * load
     *
     * @param string $file
     * @param array  $data
     *
     * @return  string
     */
    public function load($file, $data = [])
    {
        $renderer = $this->createSelf();
        return $renderer->render($file, $this->assign($data)->assign);
    }

    /**
     * getParent
     *
     * @return  mixed|null
     */
    public function parent()
    {
        if (!$this->extend) {
            return null;
        }

        if (!$this->parent) {
            $this->parent = $this->createSelf();

            $this->parent->render($this->extend, $this->assign);
        }

        return $this->parent->getBlock($this->currentBlock);
    }

    /**
     * createSelf
     *
     * @return  static
     */
    protected function createSelf()
    {
        return new static($this->getPath(), $this->config);
    }

    /**
     * extend
     *
     * @param string $name
     *
     * @return  void
     *
     * @throws \LogicException
     */
    public function extend($name)
    {
        if ($this->extend) {
            throw new \LogicException('Please just extend one file.');
        }

        $this->extend = $name;
    }

    public function setEngine($new = false){
        return $this;
    }

    public function getEngine($new = false){
        return new \stdClass;
    }

    /**
     * getBlock
     *
     * @param string $name
     *
     * @return  mixed
     */
    public function getBlock($name)
    {
        return !empty($this->block[$name]) ? $this->block[$name] : null;
    }

    /**
     * setBlock
     *
     * @param string $name
     * @param string $content
     *
     * @return  PhpRenderer  Return self to support chaining.
     */
    public function setBlock($name, $content = '')
    {
        $this->block[$name] = $content;

        return $this;
    }

    /**
     * setBlock
     *
     * @param  string $name
     *
     * @return void
     */
    public function block($name)
    {
        $this->currentBlock = $name;

        $this->getBlockQueue()->push($name);

        // Start an output buffer.
        ob_start();
    }

    /**
     * endblock
     *
     * @return  void
     */
    public function endblock()
    {
        $name = $this->getBlockQueue()->pop();

        // If this block name not exists on parent level, we just echo inner content.
        if (!empty($this->block[$name])) {
            ob_get_clean();

            echo $this->block[$name];

            return;
        }

        // Get the layout contents.
        echo $this->block[$name] = ob_get_clean();
    }

    /**
     * getBlockQueue
     *
     * @return  \SplQueue
     */
    public function getBlockQueue()
    {
        if (!$this->blockQueue) {
            $this->blockQueue = new \SplStack();
        }

        return $this->blockQueue;
    }

    /**
     * reset
     *
     * @return  static
     */
    public function reset()
    {
        $this->file = null;
        $this->extend = null;
        $this->parent = null;
        $this->assign = null;
        $this->block = [];
        $this->blockQueue = null;
        $this->currentBlock = null;
        return $this;
    }
}
