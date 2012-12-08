<?php

namespace Admingenerator\GeneratorBundle\Generator;

/**
 * This class describes New action
 * @author cedric Lombardot
 */
use Admingenerator\GeneratorBundle\Builder\Admin\BaseBuilder;

class NewAction extends Action
{
    public function __construct($name, BaseBuilder $builder)
    {
        parent::__construct($name);

        $this->setLabel('actions.new');
        $this->setIcon('icon-white icon-plus');
        $this->setClass('btn-primary');
    }
}
