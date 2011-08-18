<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

use Admingenerator\GeneratorBundle\Generator\Action;

use Admingenerator\GeneratorBundle\Generator\Column;


/**
 * This builder generate php for filters
 * @author cedric Lombardot
 */
class FiltersBuilder extends BaseBuilder
{
    
    protected $object_actions;
    
    protected $actions;

    /**
     * (non-PHPdoc)
     * @see Admingenerator\GeneratorBundle\Builder.BaseBuilder::getYamlKey()
     */
    public function getYamlKey()
    {
        return 'filters';
    }
    
    protected function findColumns()
    {
        foreach ($this->getVariable('display') as $columnName) {
            $column = new Column($columnName);
            $column->setDbType($this->getFieldGuesser()->getDbType($this->getVariable('model'), $columnName));
            $column->setFormType($this->getFieldGuesser()->getFilterType($column->getDbType()));
            $column->setFormOptions($this->getFieldGuesser()->getFilterOptions($column->getDbType()));
            
            $this->addColumn($column);
        }
    }

}