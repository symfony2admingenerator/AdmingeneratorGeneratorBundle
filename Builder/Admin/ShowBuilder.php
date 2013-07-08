<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

use Admingenerator\GeneratorBundle\Generator\Action;

/**
 * This builder generates php for show actions
 *
 * @author Eymen Gunay
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class ShowBuilder extends BaseBuilder
{
    /**
     * (non-PHPdoc)
     * @see Admingenerator\GeneratorBundle\Builder.BaseBuilder::getYamlKey()
     */
    public function getYamlKey()
    {
        return 'show';
    }
}
