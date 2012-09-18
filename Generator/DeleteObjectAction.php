<?php

namespace Admingenerator\GeneratorBundle\Generator;

/**
 *
 * This class describe an action
 * @author cedric Lombardot
 *
 */
use Admingenerator\GeneratorBundle\Builder\Admin\BaseBuilder;

class DeleteObjectAction extends Action
{
    public function __construct($name, BaseBuilder $builder)
    {
        parent::__construct($name);

        $this->setLabel('object.delete.label');
        $this->setIcon('icon-remove');
        $this->setConfirm('object.delete.confirm');
        
        $this->setParams(array(
            'pk' => '{{ '.$builder->getModelClass().'.'.$builder->getModelPrimaryKeyName().' }}',
        ));
    }
}
