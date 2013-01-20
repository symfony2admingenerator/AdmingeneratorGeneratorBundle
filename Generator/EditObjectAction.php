<?php

namespace Admingenerator\GeneratorBundle\Generator;

/**
 * This class describes object Edit action
 * @author cedric Lombardot
 */
use Admingenerator\GeneratorBundle\Builder\Admin\BaseBuilder;

class EditObjectAction extends Action
{
    public function __construct($name, BaseBuilder $builder)
    {
        parent::__construct($name);

        $this->setLabel('object.edit.label');
        $this->setIcon('icon-edit');

        $this->setParams(array(
            'pk' => '{{ '.$builder->getModelClass().'.'.$builder->getModelPrimaryKeyName().' }}',
        ));
    }
}
