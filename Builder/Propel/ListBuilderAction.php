<?php

namespace Admingenerator\GeneratorBundle\Builder\Propel;

use Admingenerator\GeneratorBundle\Builder\Admin\ListBuilderAction as AdminListBuilderAction;

use Admingenerator\GeneratorBundle\Generator\Column;

/**
 * This builder generate php for lists actions in Propel
 * @author cedric Lombardot
 */
class ListBuilderAction extends AdminListBuilderAction
{
    protected function findFilterColumns()
    {
        $filters = $this->getFilters();

        foreach ($filters['display'] as $columnName) {
            $column = new Column($columnName);
            $column->setDbType($this->getFieldGuesser()->getDbType($this->getVariable('model'), $columnName));
            $column->setFormType($this->getFieldGuesser()->getFilterType($column->getDbType()));
            $column->setFormOptions($this->getFieldGuesser()->getFilterOptions($column->getDbType(), $columnName));
            $column->setFilterOn($this->getFieldGuesser()->getPhpName($this->getVariable('model'), $columnName));

            //Set the user parameters
            $this->setUserColumnConfiguration($column);
            $this->addFilterColumn($column);
        }
    }
}
