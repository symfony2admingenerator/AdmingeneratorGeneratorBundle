<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

use Admingenerator\GeneratorBundle\Generator\Action;

/**
 * This builder generates php for edit actions
 *
 * @author cedric Lombardot
 * @author Piotr Gołębiewski <loostro@gmail.com>
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
