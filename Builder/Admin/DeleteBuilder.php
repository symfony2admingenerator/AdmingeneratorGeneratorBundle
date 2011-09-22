<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;


/**
 * This builder generate php for delete actions
 * @author cedric Lombardot
 */
class DeleteBuilder extends BaseBuilder
{
    /**
     * (non-PHPdoc)
     * @see Admingenerator\GeneratorBundle\Builder.BaseBuilder::getYamlKey()
     */
    public function getYamlKey()
    {
        return 'delete';
    }

}
