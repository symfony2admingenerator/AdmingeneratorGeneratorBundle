<?php

namespace Admingenerator\GeneratorBundle\Generator;

/**
 * This class describes object Show action
 * @author Eymen Gunay
 */
use Admingenerator\GeneratorBundle\Builder\Admin\BaseBuilder;

class ShowObjectAction extends Action
{
    public function __construct($name, BaseBuilder $builder)
    {
        parent::__construct($name);

        $this->setLabel('object.show.label');
        $this->setIcon('icon-eye-open');

        $this->setParams(array(
            'pk' => '{{ '.$builder->getModelClass().'.'.$builder->getModelPrimaryKeyName().' }}',
        ));
    }
}