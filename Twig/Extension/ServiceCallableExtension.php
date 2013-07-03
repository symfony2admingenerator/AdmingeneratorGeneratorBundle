<?php

namespace Admingenerator\GeneratorBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ServiceCallableExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('service_call', array($this, 'callServiceFuncArray'))
        );
    }

    /**
     * @param string $service
     * @param string $function
     * @param array $parameters
     *
     * @return mixed
     */
    public function callServiceFuncArray($service, $function, array $parameters = array())
    {
        return call_user_func_array(array($this->container->get($service), $function), $parameters);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'admingenerator_servicecallable';
    }
}
