<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generate form for New actions
 * @author cedric Lombardot
 */
class NewBuilderType extends NewBuilder
{
    public function getOutputName()
    {
        return 'Form/BaseType/NewType.php';
    }
    
    public function getTemplateName()
    {
        return 'EditBuilderType' . self::TWIG_EXTENSION;
    }
}