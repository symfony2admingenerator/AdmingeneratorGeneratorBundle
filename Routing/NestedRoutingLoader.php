<?php

namespace Admingenerator\GeneratorBundle\Routing;


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
