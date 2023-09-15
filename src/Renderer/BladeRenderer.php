<?php 
/**
* 
* @package view
* @author  dc.To
* @version 20230827
* @copyright ©2023 dc team all rights reserved.
*/
namespace VM\View\Renderer;

use Illuminate\View\Factory;
use Illuminate\Events\Dispatcher;
use Illuminate\View\FileViewFinder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;

/**
 * The BladeRenderer class.
 *
 * @since  2.0
 */
class BladeRenderer extends AbstractRenderer
{
    /**
     * Property blade.
     *
     * @var  Factory
     */
    protected $engine = null;

    /**
     * Property filesystem.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Property finder.
     *
     * @var FileViewFinder
     */
    protected $finder;

    /**
     * Property resolver.
     *
     * @var EngineResolver
     */
    protected $resolver;

    /**
     * Property dispatcher.
     *
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * Property compiler.
     *
     * @var CompilerEngine
     */
    protected $compiler;

    /**
     * Property customCompiler.
     *
     * @var  callable[]
     */
    protected $customCompilers = [];

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
        return $this->getEngine()->make($file, (array) $this->assign)->render();
    }

    /**
     * Method to get property Blade
     *
     * @param bool $new
     *
     * @return  Factory
     */
    public function getEngine($new = false)
    {
        if (!$this->engine || $new) {
            $this->engine = new Factory($this->getResolver(), $this->getFinder(), $this->getDispatcher());

            /** @var BladeCompiler $bladeCompiler */
            $bladeCompiler = $this->getCompiler()->getCompiler();

            // foreach (GlobalContainer::getCompilers() as $name => $callback) {
            //     BladeExtending::extend($bladeCompiler, $name, $callback);
            // }

            // foreach ($this->getCustomCompilers() as $name => $callback) {
            //     BladeExtending::extend($bladeCompiler, $name, $callback);
            // }

            // foreach (GlobalContainer::getExtensions() as $name => $callback) {
            //     $bladeCompiler->extend($callback);
            // }

            // // B/C for 4.* and 5.*
            // if (($rawTags = GlobalContainer::getRawTags()) && is_callable([$bladeCompiler, 'setRawTags'])) {
            //     $bladeCompiler->setRawTags($rawTags[0], $rawTags[1]);
            // }

            // if ($tags = GlobalContainer::getContentTags()) {
            //     $bladeCompiler->setContentTags($tags[0], $tags[1]);
            // }

            // if ($tags = GlobalContainer::getEscapedTags()) {
            //     $bladeCompiler->setEscapedContentTags($tags[0], $tags[1]);
            // }
        }

        return $this->engine;
    }

    /**
     * Method to set property blade
     *
     * @param   Factory $blade
     *
     * @return  static  Return self to support chaining.
     */
    public function setEngine($blade)
    {
        if (!($blade instanceof Factory)) {
            throw new \InvalidArgumentException('Engine object should be Illuminate\View\Environment.');
        }

        $this->engine = $blade;

        return $this;
    }

    /**
     * Method to get property Filesystem
     *
     * @return  Filesystem
     */
    public function getFilesystem()
    {
        if (!$this->filesystem) {
            $this->filesystem = new Filesystem();
        }

        return $this->filesystem;
    }

    /**
     * Method to set property filesystem
     *
     * @param   Filesystem $filesystem
     *
     * @return  static  Return self to support chaining.
     */
    public function setFilesystem($filesystem)
    {
        $this->filesystem = $filesystem;

        return $this;
    }

    /**
     * Method to get property Finder
     *
     * @return  FileViewFinder
     */
    public function getFinder()
    {
        if (!$this->finder) {
            $this->finder = new FileViewFinder($this->getFilesystem(), $this->getPath());
        }

        return $this->finder;
    }

    /**
     * Method to set property finder
     *
     * @param   FileViewFinder $finder
     *
     * @return  static  Return self to support chaining.
     */
    public function setFinder($finder)
    {
        $this->finder = $finder;

        return $this;
    }

    /**
     * Method to get property Resolver
     *
     * @return  EngineResolver
     */
    public function getResolver()
    {
        if (!$this->resolver) {
            $self = $this;

            $this->resolver = new EngineResolver();

            $this->resolver->register(
                'blade',
                function () use ($self) {
                    return $self->getCompiler();
                }
            );
        }

        return $this->resolver;
    }

    /**
     * Method to set property resolver
     *
     * @param   EngineResolver $resolver
     *
     * @return  static  Return self to support chaining.
     */
    public function setResolver($resolver)
    {
        $this->resolver = $resolver;

        return $this;
    }

    /**
     * Method to get property Dispatcher
     *
     * @return  Dispatcher
     */
    public function getDispatcher()
    {
        if (!$this->dispatcher) {
            $this->dispatcher = new Dispatcher();
        }

        return $this->dispatcher;
    }

    /**
     * Method to set property dispatcher
     *
     * @param   Dispatcher $dispatcher
     *
     * @return  static  Return self to support chaining.
     */
    public function setDispatcher($dispatcher)
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

    /**
     * Method to get property Compiler
     *
     * @return  CompilerEngine
     */
    public function getCompiler()
    {
        if (!$this->compiler) {
            $cache = $this->config('cache');
            if (!$cache) {
                throw new \InvalidArgumentException('Please set view.cache into config.');
            }
            if (!is_dir($cache)) {
                mkdir($cache, 0755, true);
            }
            $this->compiler = new CompilerEngine(new BladeCompiler($this->getFilesystem(), $cache));
        }
        return $this->compiler;
    }

    /**
     * Method to set property compiler
     *
     * @param   CompilerEngine $compiler
     *
     * @return  static  Return self to support chaining.
     */
    public function setCompiler($compiler)
    {
        $this->compiler = $compiler;

        return $this;
    }

    /**
     * addCustomCompiler
     *
     * @param   string   $name
     * @param   callable $compiler
     *
     * @return  static
     */
    public function addCustomCompiler($name, $compiler)
    {
        if (!is_callable($compiler)) {
            throw new \InvalidArgumentException('Compiler should be callable.');
        }

        $this->customCompilers[$name] = $compiler;

        return $this;
    }

    /**
     * Method to get property CustomCompiler
     *
     * @return  \callable[]
     */
    public function getCustomCompilers()
    {
        return $this->customCompilers;
    }

    /**
     * Method to set property customCompiler
     *
     * @param   \callable[] $customCompilers
     *
     * @return  static  Return self to support chaining.
     */
    public function setCustomCompilers(array $customCompilers)
    {
        $this->customCompilers = $customCompilers;

        return $this;
    }
}
