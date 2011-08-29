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
        foreach ($this->getDisplayAsColumns() as $columnName) {
            $column = new Column($columnName);
            $column->setDbType($this->getFieldGuesser()->getDbType($this->getVariable('model'), $columnName));
            $column->setFormType($this->getFieldGuesser()->getFormType($column->getDbType()));
            $column->setFormOptions($this->getFieldGuesser()->getFormOptions($column->getDbType(), $columnName));
            
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
     * Extract from the displays arrays of fieldset to keep only columns
     * 
     * @return array
     */
    protected function getDisplayAsColumns()
    {
        $display = $this->getVariable('display');
        
        if (isset($display[0])) {
            return $display;
        }
        
        //there is fieldsets
        $return = array();
            
        foreach ($display as $fieldset => $fields) {
           $return = array_merge($return, $fields);
        }

        return $return;
    }
    
    public function getFieldsets()
    {
        $display = $this->getVariable('display');
        
        if (isset($display[0])) {
            $display = array('NONE' => $display);
        }
        
        return $display;
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
    
    protected function setUserActionConfiguration(Action $action)
    {
        $options = $this->getVariable(sprintf('actions[%s]', $action->getName()),array(), true);
        
        if (null !== $options) {
            foreach ($options as $option => $value) {
                $action->setOption($option, $value);
            }
        }
    }
    
    protected function addAction(Action $action)
    {
        $this->actions[$action->getName()] = $action;
    }

    protected function findActions()
    {
        foreach ($this->getVariable('actions', array()) as $actionName => $actionParams) {
            $action = new Action($actionName);
            
            $this->setUserActionConfiguration($action);
            
            $this->addAction($action);
        }
    }
    
    /**
     * Parse a little template with twig for yaml options
     */
    public function parseStringWithTwig($template, $options = array())
    {
        $loader = new \Twig_Loader_String();
        $twig = new \Twig_Environment($loader, array(
            'autoescape' => false,
            'strict_variables' => true,
            'debug' => true,
            'cache' => $this->getGenerator()->getTempDir(),
        ));
        $this->addTwigExtensions($twig, $loader);
        $this->addTwigFilters($twig);
        
        $template = $twig->loadTemplate($template);
        
        return $template->render($options);
    }
}