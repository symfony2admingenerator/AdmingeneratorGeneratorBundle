<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generate php for edit actions
 * @author cedric Lombardot
 */
class EditBuilderTemplate extends EditBuilder
{
    public function getOutputName()
    {
        return 'Resources/views/Edit/index.html.twig';
    }
}