<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generates php for lists actions
 * @author cedric Lombardot
 */
class NestedListBuilderTemplate extends NestedListBuilder
{
    /**
     * (non-PHPdoc)
     * @see \Admingenerator\GeneratorBundle\Builder\BaseBuilder::getTemplatesToGenerate()
     */
    public function getTemplatesToGenerate()
    {
        return parent::getTemplatesToGenerate() + array(
            'NestedListBuilderTemplate'.self::TWIG_EXTENSION
                => 'Resources/views/'.$this->getBaseGeneratorName().'List/index.html.twig',
            'NestedList/ResultsBuilderTemplate'.self::TWIG_EXTENSION
                => 'Resources/views/'.$this->getBaseGeneratorName().'List/results.html.twig',
            'NestedList/RowBuilderTemplate'.self::TWIG_EXTENSION
                => 'Resources/views/'.$this->getBaseGeneratorName().'List/row.html.twig',
            'List/FiltersBuilderTemplate'.self::TWIG_EXTENSION
                => 'Resources/views/'.$this->getBaseGeneratorName().'List/filters.html.twig',
        );
    }
}
