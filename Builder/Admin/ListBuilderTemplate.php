<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generates php for lists actions
 * @author cedric Lombardot
 */
class ListBuilderTemplate extends ListBuilder
{
    /**
     * (non-PHPdoc)
     * @see \Admingenerator\GeneratorBundle\Builder\BaseBuilder::getTemplatesToGenerate()
     */
    public function getTemplatesToGenerate()
    {
        return parent::getTemplatesToGenerate() + array(
            'ListBuilderTemplate'.self::TWIG_EXTENSION
                => 'Resources/views/'.$this->getBaseGeneratorName().'List/index.html.twig',
            'List/FiltersBuilderTemplate'.self::TWIG_EXTENSION
                => 'Resources/views/'.$this->getBaseGeneratorName().'List/filters.html.twig',
            'List/ResultsBuilderTemplate'.self::TWIG_EXTENSION
                => 'Resources/views/'.$this->getBaseGeneratorName().'List/results.html.twig',
            'List/RowBuilderTemplate'.self::TWIG_EXTENSION
                => 'Resources/views/'.$this->getBaseGeneratorName().'List/row.html.twig',
        );
    }
}
