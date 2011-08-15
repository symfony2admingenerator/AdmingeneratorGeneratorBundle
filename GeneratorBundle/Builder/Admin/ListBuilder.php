<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

use Admingenerator\GeneratorBundle\Generator\Column;

use Admingenerator\GeneratorBundle\Builder\BaseBuilder;

/**
 * This builder generate php for lists actions
 * @author cedric Lombardot
 */
class ListBuilder extends BaseBuilder
{
    protected $columns;

    /**
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BaseBuilder::getYamlKey()
     */
    public function getYamlKey()
    {
        return 'list';
    }

    /**
     * Return a list of columns from ListBuilder.display
     * @return array
     */
    public function getColumns()
    {
        if(0 === count($this->columns)) {
            $this->findColumns();
        }

        return $this->columns;
    }

    protected function addColumn(Column $column)
    {
        $this->columns[$column->getName()] = $column;
    }

    protected function findColumns()
    {
        foreach ($this->getVariable('display') as $columnName) {
            $column = new Column($columnName);
            $this->addColumn($column);
        }
    }
}