<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generate php for New actions
 * @author cedric Lombardot
 */
class NewBuilderAction extends NewBuilder
{
    public function getOutputName()
    {
        return 'BaseController/NewController.php';
    }
}