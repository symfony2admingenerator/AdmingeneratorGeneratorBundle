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
           'ListBuilderTemplate' => 'Resources/views/'.$this->getBaseGeneratorName().'List/index.html.twig',
           'List/ResultsBuilderTemplate' => 'Resources/views/'.$this->getBaseGeneratorName().'List/results.html.twig',
           'List/FiltersBuilderTemplate' => 'Resources/views/'.$this->getBaseGeneratorName().'List/filters.html.twig',
           'List/FormBuilderTemplate' => 'Resources/views/'.$this->getBaseGeneratorName().'List/form.html.twig',
       );
    }
}
