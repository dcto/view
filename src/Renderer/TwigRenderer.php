<?php 

/**
 * @package \VM\View\Renderer
 */
namespace VM\View\Renderer;

use VM\View\Renderer;

/**
 * Class TwigRenderer
 *
 * @since 2.0
 */
class TwigRenderer extends Renderer
{
    protected $config = [
        
    ];
    /**
     * Property loader.
     *
     * @var  \TwigLoaderInterface
     */
    protected $loader;

    /**
     * Property debugExtension.
     *
     * @var  \Twig\Extension\DebugExtension
     */
    protected $debugExtension;
    
    /**
     * render
     *
     * @param string       $file
     * @param array|object $data
     *
     * @throws  \UnexpectedValueException
     * @return  string
     */
    public function render($file, ...$data)
    {
        return $this->getEngine()->render($this->load($file), $this->assign(...$data)->assign);
    }

    /**
     * getLoader
     *
     * @return  \Twig\Loader\LoaderInterface
     */
    public function getLoader($new = false)
    {
        if (!$this->loader || $new) {
            $this->loader = new \Twig\Loader\FilesystemLoader($this->getPath());
        }
        return $this->loader;
    }

    /**
     * setLoader
     *
     * @param   \Twig\Loader\LoaderInterface $loader
     *
     * @return  TwigRenderer  Return self to support chaining.
     */
    public function setLoader(\Twig\Loader\LoaderInterface $loader = null)
    {
        $this->loader = $loader;
        return $this;
    }

    /**
     * getTwig
     *
     * @param bool $new
     *
     * @return  \Twig\Environment
     */
    public function getEngine($new = false)
    {
        if (!($this->engine instanceof \Twig\Environment) || $new) {
            $this->engine = new \Twig\Environment($this->getLoader($new), $this->config);
        }
        return $this->engine;
    }

    /**
     * setTwig
     *
     * @param   \Twig\Environment $twig
     *
     * @return  TwigRenderer  Return self to support chaining.
     */
    public function setEngine($twig = null)
    {
        if (!($twig instanceof \Twig\Environment)) {
            throw new \InvalidArgumentException('Invalid Engine Instaceof Twig\Environment');
        }
        $this->engine = $twig;
        
        return $this;
    }
}
