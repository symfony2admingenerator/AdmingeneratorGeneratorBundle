<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

use Admingenerator\GeneratorBundle\Generator\Action;


/**
 * This builder generate php for lists actions
 * @author cedric Lombardot
 */
class ListBuilder extends BaseBuilder
{
    
    protected $object_actions;
    
    protected $actions;

    /**
     * (non-PHPdoc)
     * @see Admingenerator\GeneratorBundle\Builder.BaseBuilder::getYamlKey()
     */
    public function getYamlKey()
    {
        return 'list';
    }

    /**
     * Find filters parameters informations
     */
    public function getFilters()
    {
        return $this->getGenerator()->getFromYaml('builders.filters.params');
    }
    
    
    /**
     * Return a list of action from list.object_actions
     * @return array
     */
    public function getObjectActions()
    {
        if(0 === count($this->object_actions)) {
            $this->findObjectActions();
        }

        return $this->object_actions;
    }
    
    protected function addObjectAction(Action $action)
    {
        $this->object_actions[$action->getName()] = $action;
    }

    protected function findObjectActions()
    {
        foreach ($this->getVariable('object_actions') as $actionName => $actionParams) {
            $action = new Action($actionName);
            $this->addObjectAction($action);
        }
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
        foreach ($this->getVariable('actions') as $actionName => $actionParams) {
            $action = new Action($actionName);
            $this->addAction($action);
        }
    }
}