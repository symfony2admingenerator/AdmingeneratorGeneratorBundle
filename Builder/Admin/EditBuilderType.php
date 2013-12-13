<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generates form for edit actions
 * @author cedric Lombardot
 */
class EditBuilderType extends EditBuilder
{
    public function getOutputName()
    {
        return 'Form/Base'.$this->getBaseGeneratorName().'Type/EditType.php';
    }
}
