<?php

namespace Admingenerator\GeneratorBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use Admingenerator\GeneratorBundle\ClassLoader\AdmingeneratedClassLoader;

class AdmingeneratorGeneratorBundle extends Bundle
{
    public function boot()
    {
        $AdmingeneratedClassLoader = new AdmingeneratedClassLoader;
        $AdmingeneratedClassLoader->setBasePath($this->container->getParameter('kernel.cache_dir'));
        $AdmingeneratedClassLoader->register();
    }
}
