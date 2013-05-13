<?php

namespace Admingenerator\GeneratorBundle\Generator\Action\Batch;

use Admingenerator\GeneratorBundle\Builder\Admin\BaseBuilder;
use Admingenerator\GeneratorBundle\Generator\Action;

/**
 * This class describes batch Delete action
 * @author cedric Lombardot
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class DeleteAction extends Action
{
    public function __construct($name, BaseBuilder $builder)
    {
        parent::__construct($name, $type = 'batch');

        $this->setIcon('icon-remove');
        $this->setLabel('action.batch.delete.label');
        $this->setConfirm('action.batch.delete.confirm');
        $this->setCsrfProtected(true);
    }
}
