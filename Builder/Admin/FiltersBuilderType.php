<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generates form for Filters
 * @author cedric Lombardot
 */
class FiltersBuilderType extends FiltersBuilder
{
    public function getOutputName()
    {
        return 'Form/Base'.$this->getBaseGeneratorName().'Type/FiltersType.php';
    }

    public function getTemplateName()
    {
        return 'EditBuilderType' . self::TWIG_EXTENSION;
    }
}
