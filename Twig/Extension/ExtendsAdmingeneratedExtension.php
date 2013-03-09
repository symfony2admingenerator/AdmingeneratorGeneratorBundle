<?php

namespace  Admingenerator\GeneratorBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Admingenerator\GeneratorBundle\Twig\TokenParser\ExtendsAdmingeneratedTokenParser;

class ExtendsAdmingeneratedExtension extends \Twig_Extension
{
    protected $loader;

    public function __construct(\Twig_Loader_Filesystem $loader)
    {
        $this->loader = $loader;
    }

    public function addCachePath(ContainerInterface $container)
    {
        $this->loader->addPath($container->getParameter('kernel.cache_dir'));
    }

    public function getTokenParsers()
    {
        return array(
            new ExtendsAdmingeneratedTokenParser(),
        );
    }

    public function getName()
    {
        return 'extends_admingenerated';
    }
}
