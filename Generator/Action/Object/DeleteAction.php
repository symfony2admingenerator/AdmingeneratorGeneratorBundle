<?php

namespace Admingenerator\GeneratorBundle\Generator\Action\Object;

use Admingenerator\GeneratorBundle\Builder\Admin\BaseBuilder;
use Admingenerator\GeneratorBundle\Generator\Action;

/**
 * This class describes object Delete action
 *
 * @author cedric Lombardot
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class DeleteAction extends Action
{
    public function __construct($name, BaseBuilder $builder)
    {
        parent::__construct($name, $type = 'object');

        $this->setIcon('icon-remove');
        $this->setLabel('action.object.delete.label');
        $this->setConfirm('action.object.delete.confirm');
        $this->setCsrfProtected(true);

        $this->setParams(array(
            'pk' => '{{ '.$builder->getModelClass().'.'.$builder->getModelPrimaryKeyName().' }}',
        ));
    }
}
