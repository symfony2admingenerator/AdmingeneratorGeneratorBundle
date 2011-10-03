<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generate php for New actions
 * @author cedric Lombardot
 */
class NewBuilderTemplate extends NewBuilder
{
    public function getOutputName()
    {
        return 'Resources/views/'.$this->getBaseGeneratorName().'New/index.html.twig';
    }

    public function getTemplateName()
    {
        return 'EditBuilderTemplate' . self::TWIG_EXTENSION;
    }
}
