<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generate php for delete actions
 * @author cedric Lombardot
 */
class DeleteBuilderAction extends DeleteBuilder
{
    public function getOutputName()
    {
        return $this->getGenerator()->getGeneratedControllerFolder().'/DeleteController.php';
    }
}
