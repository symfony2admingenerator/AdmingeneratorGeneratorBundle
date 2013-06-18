<?php

namespace Admingenerator\GeneratorBundle\Tests\Routing;

use Symfony\Component\Routing\RouteCollection;

use Admingenerator\GeneratorBundle\Tests\TestCase;

use Symfony\Component\Config\FileLocator;

use Admingenerator\GeneratorBundle\Routing\RoutingLoader;

class RoutingLoaderTest extends TestCase
{
    public function setUp()
    {
        if (!file_exists('/host/admingen/src/Admingenerator/DemoBundle/Controller/') && !file_exists('c:\admingen\src\Admingenerator\DemoBundle\Controller\\')) {
            $this->markTestSkipped('No DemoBundle found');
        }
    }

    public function testLoad()
    {
        if (file_exists('/host/admingen/src/Admingenerator/DemoBundle/Controller/')) {
            $routing = new RoutingLoader(new FileLocator(array()));
            $routes = $routing->load('/host/admingen/src/Admingenerator/DemoBundle/Controller/', 'admingenerated');
            $this->assertInstanceOf('Symfony\Component\Routing\RouteCollection', $routes);            
        }

        if (file_exists('c:\admingen\src\Admingenerator\DemoBundle\Controller\\')) {
            $routing = new RoutingLoader(new FileLocator(array()));
            $routes = $routing->load('c:\admingen\src\Admingenerator\DemoBundle\Controller\\', 'admingenerated');
            $this->assertInstanceOf('Symfony\Component\Routing\RouteCollection', $routes);
        }
    }
}
