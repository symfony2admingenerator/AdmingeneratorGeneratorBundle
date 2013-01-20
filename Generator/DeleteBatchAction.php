<?php

namespace Admingenerator\GeneratorBundle\Generator;

/**
 * This class describes batch Delete action
 * @author cedric Lombardot
 */
use Admingenerator\GeneratorBundle\Builder\Admin\BaseBuilder;

class DeleteBatchAction extends Action
{
    public function __construct($name, BaseBuilder $builder)
    {
        parent::__construct($name);

        $this->setLabel('batch.delete.label');
        $this->setIcon('icon-remove');
        $this->setConfirm('batch.delete.confirm');
    }
}
