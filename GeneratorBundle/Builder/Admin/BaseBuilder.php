<?php 

namespace Admingenerator\GeneratorBundle\Builder\Admin;

use Admingenerator\GeneratorBundle\Builder\BaseBuilder as GenericBaseBuilder;

use Admingenerator\GeneratorBundle\Generator\Column;

class BaseBuilder extends GenericBaseBuilder
{
    
    protected $columns;
    
    /**
     * Return a list of columns from list.display
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
            $column->setDbType($this->getFieldGuesser()->getDbType($this->getVariable('model'), $columnName));
            $column->setFormType($this->getFieldGuesser()->getFormType($column->getDbType()));
            $column->setFormOptions($this->getFieldGuesser()->getFormOptions($column->getDbType()));
            
            $this->addColumn($column);
        }
    }
    
    protected function getFieldGuesser()
    {
        return $this->getGenerator()->getFieldGuesser();
    }
}