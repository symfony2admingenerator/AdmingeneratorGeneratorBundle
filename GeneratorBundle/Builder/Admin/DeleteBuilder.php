<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

use Admingenerator\GeneratorBundle\Builder\BaseBuilder;

/**
 * This builder generate php for delete actions
 * @author cedric Lombardot
 */
class DeleteBuilder extends BaseBuilder
{
    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BaseBuilder::getYamlKey()
     */
    public function getYamlKey()
    {
        return 'delete';
    }

}