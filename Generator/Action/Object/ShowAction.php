<?php

namespace Admingenerator\GeneratorBundle\Generator\Action\Object;

use Admingenerator\GeneratorBundle\Builder\Admin\BaseBuilder;
use Admingenerator\GeneratorBundle\Generator\Action;

/**
 * This class describes object Show action
 * @author Eymen Gunay
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class ShowAction extends Action
{
    public function __construct($name, BaseBuilder $builder)
    {
        parent::__construct($name, 'object');

        $this->setIcon('icon-eye-open');
        $this->setLabel('action.object.show.label');
        
        $this->setRoute($builder->getBaseActionsRoute().'_show');

        $this->setParams(array(
            'pk' => '{{ '.$builder->getModelClass().'.'.$builder->getModelPrimaryKeyName().' }}',
        ));
    }
}
