<?php

namespace Admingenerator\GeneratorBundle\Generator\Action\Generic;

use Admingenerator\GeneratorBundle\Builder\Admin\BaseBuilder;
use Admingenerator\GeneratorBundle\Generator\Action;

/**
 * This class describes generic Save and Add action
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class SaveAndAddAction extends Action
{
    public function __construct($name, BaseBuilder $builder)
    {
        parent::__construct($name, 'generic');

        $this->setSubmit(true);
        $this->setClass('btn-primary');
        $this->setIcon('icon-plus icon-white');
        $this->setLabel('action.generic.save-and-add');
    }
}
