<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generates php for edit actions
 * @author cedric Lombardot
 */
class EditBuilderAction extends EditBuilder
{
    public function getOutputName()
    {
        return $this->getGenerator()->getGeneratedControllerFolder().'/EditController.php';
    }
}
