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
        return sprintf(
                '%s/Resources/views/List/index.html.twig',
                $this->getVariable('bundle_name')
                );
    }
}