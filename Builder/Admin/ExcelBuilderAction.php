<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generates php for lists actions
 * @author cedric Lombardot
 * @author Bob van de Vijver
 */
class ExcelBuilderAction extends ExcelBuilder
{
    public function getOutputName()
    {
        return $this->getGenerator()->getGeneratedControllerFolder().'/ExcelController.php';
    }
}
