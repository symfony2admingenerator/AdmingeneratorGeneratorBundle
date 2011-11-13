<?php

namespace Admingenerator\GeneratorBundle\Builder;

use Symfony\Component\Templating\TemplateNameParser;

use Symfony\Component\Config\FileLocator;

use Symfony\Bundle\FrameworkBundle\Templating\Loader\TemplateLocator;

use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;

use Symfony\Component\HttpFoundation\ParameterBag;

use TwigGenerator\Builder\BaseBuilder as GenericBaseBuilder;

abstract class BaseBuilder extends GenericBaseBuilder
{
    /**
     * @var array
     */
    protected $twigFilters = array(
        'addslashes',
        'var_export',
        'is_numeric',
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

    public function __construct()
    {
        parent::__construct();
        $this->variables = new ParameterBag(array());
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
     * @return string the YamlKey
     */
    public function getYamlKey()
    {
        return $this->getSimpleClassName();
    }

    public function setVariables(array $variables)
    {
        $variables = new ParameterBag($variables);
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
     * Get model class from model param
     * @return string
     */
    public function getModelClass()
    {
        return $this->getSimpleClassName($this->getVariable('model'));
    }

}
