<?php

namespace Admingenerator\GeneratorBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Admingenerator\GeneratorBundle\ClassLoader\AdmingeneratedClassLoader;
use Admingenerator\GeneratorBundle\DependencyInjection\Compiler\ValidatorCompilerPass;

class AdmingeneratorGeneratorBundle extends Bundle
{
    public function boot()
    {
        $AdmingeneratedClassLoader = new AdmingeneratedClassLoader;
        $AdmingeneratedClassLoader->setBasePath($this->container->getParameter('kernel.cache_dir'));
        $AdmingeneratedClassLoader->register();
    }
    
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ValidatorCompilerPass());
    }
}
