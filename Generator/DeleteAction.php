<?php

namespace Admingenerator\GeneratorBundle\Generator;

/**
 *
 * This class describe an action
 * @author cedric Lombardot
 *
 */
use Admingenerator\GeneratorBundle\Builder\Admin\BaseBuilder;

class DeleteAction extends Action
{
    public function __construct($name, BaseBuilder $builder)
    {
        parent::__construct($name);

        $this->setConfirm('{% trans from "Admingenerator" %}form.delete.confirm{% endtrans %}');
        $this->setParams(array(
            'pk' => '{{ '.$builder->getModelClass().'.'.$builder->getModelPrimaryKeyName().' }}',
        ));
    }
}
