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
        parent::__construct($name, 'batch');

        $this->setIcon('glyphicon-remove');
        $this->setLabel('action.batch.delete.label');
        $this->setConfirm('action.batch.delete.confirm');
        $this->setCsrfProtected(true);
        
        $this->setOptions(array(
            'success' => 'action.batch.delete.success',
            'error' => 'action.batch.delete.success',
            'i18n' => 'Admingenerator'
        ));
    }
}
