<?php

namespace Admingenerator\GeneratorBundle\Generator;

/**
 *
 * This class describe an action
 * @author cedric Lombardot
 *
 */
use Admingenerator\GeneratorBundle\Builder\Admin\BaseBuilder;

class ListAction extends Action
{
    public function __construct($name, BaseBuilder $builder)
    {
        parent::__construct($name);

        $this->setLabel('actions.list.label');
        $this->setIcon('icon-list-alt');
    }
}
