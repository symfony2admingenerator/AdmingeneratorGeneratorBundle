<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generate php for empty actions
 * @author cedric Lombardot
 */
class EmptyBuilderAction extends BaseBuilder
{
    /**
     * (non-PHPdoc)
     * @see Admingenerator\GeneratorBundle\Builder.BuilderInterface::getDefaultTemplateDirs()
     */
    public function getTemplateDirs()
    {
        return array(realpath(dirname(__FILE__).'/../../Resources/templates'));
    }
}
