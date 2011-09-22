<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

use Admingenerator\GeneratorBundle\Generator\Action;

use Admingenerator\GeneratorBundle\Generator\Column;

/**
 * This builder generate php for new actions
 * @author cedric Lombardot
 */
class NewBuilder extends BaseBuilder
{
    /**
     * (non-PHPdoc)
     * @see Admingenerator\GeneratorBundle\Builder.BaseBuilder::getYamlKey()
     */
    public function getYamlKey()
    {
        return 'new';
    }

}
