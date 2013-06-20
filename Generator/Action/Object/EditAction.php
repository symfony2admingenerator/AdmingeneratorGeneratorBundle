<?php

namespace Admingenerator\GeneratorBundle\Generator\Action\Object;

use Admingenerator\GeneratorBundle\Builder\Admin\BaseBuilder;
use Admingenerator\GeneratorBundle\Generator\Action;

/**
 * This class describes object Edit action
 * @author cedric Lombardot
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class EditAction extends Action
{
    public function __construct($name, BaseBuilder $builder)
    {
        parent::__construct($name, 'object');

        $this->setIcon('icon-edit');
        $this->setLabel('action.object.edit.label');
        
        $this->setRoute($builder->getBaseActionsRoute().'_edit');

        $this->setParams(array(
            'pk' => '{{ '.$builder->getModelClass().'.'.$builder->getModelPrimaryKeyName().' }}',
        ));
    }
}
