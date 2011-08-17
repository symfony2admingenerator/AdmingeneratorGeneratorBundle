<?php

namespace Admingenerator\GeneratorBundle\Builder;

/**
 * Inteface to define structure of the builders
 *
 * @author cedric Lombardot
 *
 */

use Symfony\Component\Templating\TemplateNameParser;

use Symfony\Component\Config\FileLocator;

use Symfony\Bundle\FrameworkBundle\Templating\Loader\TemplateLocator;

use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;

use Symfony\Component\HttpFoundation\ParameterBag;

abstract class BaseBuilder implements BuilderInterface
{
    const TWIG_EXTENSION = '.php.twig';

    /**
     * @var Generator the generator element
     */
    protected $generator;

    /**
     * @var array a list of templates directories
     */
    protected $templateDirectories = array();

    /**
     * @var string
     */
    protected $templateName;

    /**
     * @var string
     */
    protected $outputName;

    /**
     * @var boolean
     */
    protected $mustOverwriteIfExists = false;

    /**
     * @var array
     */
    protected $twigFilters = array(
		'var_export',
        'ucfirst',
        '\Doctrine\Common\Util\Inflector::classify',
        'substr',
    );
    
     /**
     * @var array
     */
    protected $twigExtensions = array(
        'Admingenerator\GeneratorBundle\Twig\Extension\EchoExtension',
    );
    
    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::__construct()
     */
    public function __construct()
    {
        $this->templateDirectories = $this->getDefaultTemplateDirs();
        $this->templateName = $this->getDefaultTemplateName();
        $this->variables = new ParameterBag(array());
    }

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::setGenerator()
     */
    public function setGenerator(Generator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::getGenerator()
     */
    public function getGenerator()
    {
        return $this->generator;
    }

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::addTemplateDir()
     */
    public function addTemplateDir($templateDir)
    {
        $this->templateDirectories[$templateDir] = $templateDir;
    }

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::setTemplateDirs()
     */
    public function setTemplateDirs($templateDirs)
    {
        $this->templateDirectories = $templateDirs;
    }

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::getTemplateDirs()
     */
    public function getTemplateDirs()
    {
        return $this->templateDirectories;
    }

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::getDefaultTemplateDirs()
     */
    public function getDefaultTemplateDirs()
    {
         
        return array(realpath(dirname(__FILE__).'/../Resources/templates/Doctrine'));
    }

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::setTemplateName()
     */
    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;
    }

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::getTemplateName()
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::getDefaultTemplateName()
     */
    public function getDefaultTemplateName()
    {
        return $this->getSimpleClassName(). self::TWIG_EXTENSION;
    }

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::getSimpleClassName()
     */
    public function getSimpleClassName($class = null)
    {
        if (null === $class) {
            $class = get_class($this);
        }
         
        $classParts = explode('\\', $class);
        $simpleClassName = array_pop($classParts);
        return $simpleClassName;
    }

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::setOutputName()
     */
    public function setOutputName($outputName)
    {
        $this->outputName = $outputName;
    }

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::getOutputName()
     */
    public function getOutputName()
    {
        return $this->outputName;
    }

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::mustOverwriteIfExists()
     */
    public function mustOverwriteIfExists()
    {
        return $this->mustOverwriteIfExists;
    }
    
    /**
     * (non-PHPdoc)
     * @see Admingenerator\GeneratorBundle\Builder.BuilderInterface::setMustOverwriteIfExists()
     */
    public function setMustOverwriteIfExists($status = true)
    {
        $this->mustOverwriteIfExists = $status;
    }

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::setVariables()
     */
    public function setVariables($variables)
    {
        if (is_array($variables)) {
            $variables = new ParameterBag($variables);
        }
         
        $this->variables = $variables;
    }

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::getVariables()
     */
    public function getVariables()
    {
        return $this->variables->all();
    }

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::hasVariable()
     */
    public function hasVariable($key)
    {
        return $this->variables->has($key);
    }

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::getVariable()
     */
    public function getVariable($key, $default = null, $deep = false)
    {
        return $this->variables->get($key, $default, $deep);
    }

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::writeOnDisk()
     */
    public function writeOnDisk($outputDirectory)
    {
        $path = $outputDirectory . DIRECTORY_SEPARATOR . $this->getOutputName();
        $dir = dirname($path);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (!file_exists($path) || (file_exists($path) && $this->mustOverwriteIfExists)) {
            file_put_contents($path, $this->getCode());
        }
    }

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::getCode()
     */
    public function getCode()
    {
        $locator = new TemplateLocator(new FileLocator($this->getTemplateDirs()));
        $templateNameParser = new TemplateNameParser();
        $loader = new FilesystemLoader($locator, $templateNameParser); 
        $twig = new \Twig_Environment($loader, array(
            'autoescape' => false,
            'strict_variables' => true,
            'debug' => true,
            'cache' => $this->getGenerator()->getTempDir(),
        ));
        
        $this->addTwigExtensions($twig, $loader);
        $this->addTwigFilters($twig);
        $template = $twig->loadTemplate($this->getTemplateName());

        $variables = $this->getVariables();
        $variables['builder'] = $this;

        return $template->render($variables);
    }

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::addTwigFilters()
     */
    public function addTwigFilters(\Twig_Environment $twig)
    {
        foreach ($this->twigFilters as $twigFilter) {
            if (($pos = strpos($twigFilter, ':')) !== false) {
                $twigFilterName = substr($twigFilter, $pos + 2);
            } else {
                $twigFilterName = $twigFilter;
            }
            $twig->addFilter($twigFilterName, new \Twig_Filter_Function($twigFilter));
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::addTwigExtensions()
     */
    public function addTwigExtensions(\Twig_Environment $twig, FilesystemLoader $loader)
    {
        foreach ($this->twigExtensions as $twigExtensionName) {
            $twigExtension = new $twigExtensionName($loader);
            $twig->addExtension($twigExtension);
        }
    }

    /**
     * @return string the YamlKey
     */
    public function getYamlKey()
    {
        return $this->getSimpleClassName();
    }

    /**
     * Get model class from model param
     * @return string
     */
    public function getModelClass()
    {
        return $this->getSimpleClassName($this->getVariable('model'));
    }

}
