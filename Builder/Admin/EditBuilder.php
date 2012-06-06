<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generate php for edit actions
 * @author cedric Lombardot
 */
class EditBuilder extends BaseBuilder
{
    /**
     * (non-PHPdoc)
     * @see Admingenerator\GeneratorBundle\Builder.BaseBuilder::getYamlKey()
     */
    public function getYamlKey()
    {
        return 'edit';
    }

}
