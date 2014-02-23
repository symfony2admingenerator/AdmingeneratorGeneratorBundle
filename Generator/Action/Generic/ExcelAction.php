<?php

namespace Admingenerator\GeneratorBundle\Generator\Action\Generic;

use Admingenerator\GeneratorBundle\Builder\Admin\BaseBuilder;
use Admingenerator\GeneratorBundle\Generator\Action;

/**
 * This class describes generic Excel action
 * @author cedric Lombardot
 * @author Piotr Gołębiewski <loostro@gmail.com>
 * @author Bob van de Vijver
 */
class ExcelAction extends Action
{
  public function __construct($name, BaseBuilder $builder)
  {
    parent::__construct($name, 'generic');

    $this->setClass('btn-primary');
    $this->setIcon('icon-white icon-print');
    $this->setLabel('action.generic.excel');
  }
}
