<?php

namespace Admingenerator\GeneratorBundle\Routing;

class NestedRoutingLoader extends RoutingLoader
{
    public function load($resource, $type = null)
    {
        $this->actions['nested_move'] = array(
            'pattern'      => '/nested-move/{dragged}/{action}/{dropped}',
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
