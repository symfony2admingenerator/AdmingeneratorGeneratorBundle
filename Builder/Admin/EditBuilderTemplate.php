<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generates php for edit actions
 * @author cedric Lombardot
 */
class EditBuilderTemplate extends EditBuilder
{
    /**
     * (non-PHPdoc)
     * @see \Admingenerator\GeneratorBundle\Builder\BaseBuilder::getTemplatesToGenerate()
     */
    public function getTemplatesToGenerate()
    {
        return parent::getTemplatesToGenerate() + array(
                'EditBuilderTemplate'.self::TWIG_EXTENSION
                    => 'Resources/views/'.$this->getBaseGeneratorName().'Edit/index.html.twig',
                'Edit/FormBuilderTemplate'.self::TWIG_EXTENSION
                    => 'Resources/views/'.$this->getBaseGeneratorName().'Edit/form.html.twig',
        );
    }
}
