<?php

namespace Admingenerator\GeneratorBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ConfigExtension extends \Twig_Extension
{
    protected $container;
 
    public function __construct(ContainerInterface $container) 
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'admingenerator_config' => new \Twig_Function_Method($this, 'getAdmingeneratorConfig'),
        );
    }
    
    /**
     * Returns admingenerator parameter
     * 
     * @param string $name
     * @return string Parameter value
     */
    function getAdmingeneratorConfig($name)
    {
        return $this->container->getParameter('admingenerator.'.$name);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'admingenerator_config';
    }
}