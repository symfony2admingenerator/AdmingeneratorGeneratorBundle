<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generate php for lists actions
 * @author cedric Lombardot
 */
class ListBuilderTemplate extends ListBuilder
{
    public function getOutputName()
    {
        return 'Resources/views/'.$this->getBaseGeneratorName().'List/index.html.twig';
    }
}
