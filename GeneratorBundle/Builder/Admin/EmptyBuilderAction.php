<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

use Admingenerator\GeneratorBundle\Builder\BaseBuilder;
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
    public function getDefaultTemplateDirs()
    {
        return array(realpath(dirname(__FILE__).'/../../Resources/templates'));
    }
}