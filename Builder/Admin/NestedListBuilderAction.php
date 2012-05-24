<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generate php for lists actions
 * @author cedric Lombardot
 */
class NestedListBuilderAction extends NestedListBuilder
{
    public function getOutputName()
    {
        return $this->getGenerator()->getGeneratedControllerFolder().'/ListController.php';
    }
}
