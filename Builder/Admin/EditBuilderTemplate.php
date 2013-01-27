<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generates php for edit actions
 * @author cedric Lombardot
 */
class EditBuilderTemplate extends EditBuilder
{
    public function getOutputName()
    {
        return 'Resources/views/'.$this->getBaseGeneratorName().'Edit/index.html.twig';
    }
}
