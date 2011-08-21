<?php 

namespace Admingenerator\GeneratorBundle\Builder\Admin;

use Admingenerator\GeneratorBundle\Builder\BaseBuilder as GenericBaseBuilder;

use Admingenerator\GeneratorBundle\Generator\Column;

use Admingenerator\GeneratorBundle\Generator\Action;

class BaseBuilder extends GenericBaseBuilder
{
    
    protected $columns;
    
    protected $actions;
    
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
            
            //Set the user parameters
            $this->setUserColumnConfiguration($column);
            
            $this->addColumn($column);
        }
    }
    
    protected function setUserColumnConfiguration(Column $column)
    {
        $options = $this->getVariable(sprintf('fields[%s]', $column->getName()),array(), true);
        
        foreach ($options as $option => $value) {
            $column->setOption($option, $value);
        }
    }
    
    public function getFieldGuesser()
    {
        return $this->getGenerator()->getFieldGuesser();
    }
    
    /**
     * Return a list of action from list.actions
     * @return array
     */
    public function getActions()
    {
        if(0 === count($this->actions)) {
            $this->findActions();
        }

        return $this->actions;
    }
    
    protected function addAction(Action $action)
    {
        $this->actions[$action->getName()] = $action;
    }

    protected function findActions()
    {
        foreach ($this->getVariable('actions', array()) as $actionName => $actionParams) {
            $action = new Action($actionName);
            $this->addAction($action);
        }
    }
}