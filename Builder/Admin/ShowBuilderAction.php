<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generate php for edit actions
 * @author Eymen Gunay
 */
class ShowBuilderAction extends ShowBuilder
{
    public function getOutputName()
    {
        return $this->getGenerator()->getGeneratedControllerFolder().'/ShowController.php';
    }
}
