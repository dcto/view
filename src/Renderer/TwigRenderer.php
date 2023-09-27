<?php 
/**
 * @package \VM\View\Renderer
 */
namespace VM\View\Renderer;

/**
 * Class TwigRenderer
 *
 * @since 2.0
 */
class TwigRenderer
{
    /**
     * Property loader.
     *
     * @var  \TwigLoaderInterface
     */
    protected $loader = null;

    /**
     * Property extensions.
     *
     * @var  \Twig\Extension\ExtensionInterface[]
     */
    protected $extension = 'twig';

    /**
     * Property debugExtension.
     *
     * @var  \Twig\Extension\DebugExtension
     */
    protected $debugExtension = null;
    
    /**
     * render
     *
     * @param string       $file
     * @param array|object $data
     *
     * @throws  \UnexpectedValueException
     * @return  string
     */
    public function render($file, $data = [])
    {
        return $this->getEngine()->render($file, $this->assign($data)->assign);
    }

    /**
     * getLoader
     *
     * @return  \Twig\Loader\LoaderInterface
     */
    public function getLoader()
    {
        if (!$this->loader) {
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
    public function setLoader(\Twig\Loader\LoaderInterface $loader)
    {
        $this->loader = $loader;
        return $this;
    }

    /**
     * addExtension
     *
     * @param \Twig\Extension\ExtensionInterface $extension
     *
     * @return  static
     */
    public function addExtension(\Twig\Extension\ExtensionInterface $extension)
    {
        $this->extensions[] = $extension;
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
            $this->engine = new \Twig\Environment($this->getLoader(), $this->config);
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
    public function setEngine($twig)
    {
        if (!($twig instanceof \Twig\Environment)) {
            throw new \InvalidArgumentException('Engine object should be Twig\Environment');
        }
        $this->engine = $twig;
        
        return $this;
    }

    /**
     * Method to get property DebugExtension
     *
     * @return  \Twig\Extension\DebugExtension
     */
    public function getDebugExtension()
    {
        if (!$this->debugExtension) {
            $this->debugExtension = new \Twig\Extension\DebugExtension();
        }
        return $this->debugExtension;
    }

    /**
     * Method to set property debugExtension
     *
     * @param   \Twig\Extension\ExtensionInterface $debugExtension
     *
     * @return  static  Return self to support chaining.
     */
    public function setDebugExtension(\Twig\Extension\ExtensionInterface $debugExtension)
    {
        $this->debugExtension = $debugExtension;

        return $this;
    }

    /**
     * Method to get property Extensions
     *
     * @return  \Twig\Extension\ExtensionInterface[]
     */
    // public function getExtensions()
    // {
    //     return $this->extensions;
    // }

    /**
     * Method to set property extensions
     *
     * @param   \Twig\Extension\ExtensionInterface[] $extensions Twig extenions
     *
     * @return  static  Return self to support chaining.
     */
    // public function setExtensions($extensions)
    // {
    //     $this->extensions = $extensions;
    //     return $this;
    // }
}
