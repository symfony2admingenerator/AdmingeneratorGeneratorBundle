<?php

namespace Admingenerator\GeneratorBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
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
            new \Twig_SimpleFunction('admingenerator_config', array($this, 'getAdmingeneratorConfig')),
        );
    }

    /**
     * Returns admingenerator parameter
     *
     * @param  string $name
     * @return string Parameter value
     */
    public function getAdmingeneratorConfig($name)
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
