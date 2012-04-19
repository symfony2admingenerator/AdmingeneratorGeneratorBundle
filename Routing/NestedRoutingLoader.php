<?php

namespace Admingenerator\GeneratorBundle\Routing;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

class NestedRoutingLoader extends RoutingLoader
{
    public function load($resource, $type = null)
    {
        $this->actions['nested_append'] = array(
            'pattern'      => '/append-node/{source}/to/{to}',
            'defaults'     => array(),
            'requirements' => array(),
            'controller'   => 'list',
        );

        return parent::load($resource, $type);
    }

    public function supports($resource, $type = null)
    {
        return 'admingenerator_nested' == $type;
    }
}
