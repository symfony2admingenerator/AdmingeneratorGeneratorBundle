<?php

namespace Admingenerator\GeneratorBundle\Generator;

/**
 * This class describes Save and Add action
 * @author loostro <loostro@gmail.com>
 */
use Admingenerator\GeneratorBundle\Builder\Admin\BaseBuilder;

class SaveAndAddAction extends Action
{
    public function __construct($name, BaseBuilder $builder)
    {
        parent::__construct($name);

        $this->setSubmit(true);
        $this->setLabel('actions.save-and-add');
    }
}