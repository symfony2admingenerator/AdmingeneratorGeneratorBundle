<?php


namespace Admingenerator\GeneratorBundle\Tests\Routing;

use Symfony\Component\Routing\RouteCollection;

use Admingenerator\GeneratorBundle\Tests\TestCase;

use Symfony\Component\Config\FileLocator;

use Admingenerator\GeneratorBundle\Routing\RoutingLoader;

class RoutingLoaderTest extends TestCase
{

    public function testLoad()
    {

        $routing = new RoutingLoader(new FileLocator(array()));
        $routes = $routing->load('/host/admingen/src/Admingenerator/DemoBundle/Controller/', 'admingenerated');
        $this->assertInstanceOf('Symfony\Component\Routing\RouteCollection', $routes);

        $routing = new RoutingLoader(new FileLocator(array()));
        $routes = $routing->load('c:\admingen\src\Admingenerator\DemoBundle\Controller\\', 'admingenerated');
        $this->assertInstanceOf('Symfony\Component\Routing\RouteCollection', $routes);
    }
}
