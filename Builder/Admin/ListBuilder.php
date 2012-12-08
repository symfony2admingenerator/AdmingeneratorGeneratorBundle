<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

use Symfony\Component\DependencyInjection\Container;

use Admingenerator\GeneratorBundle\Generator\Column;

use Admingenerator\GeneratorBundle\Generator\Action;

/**
 * This builder generate php for lists actions
 * @author cedric Lombardot
 */
class ListBuilder extends BaseBuilder
{

    protected $object_actions;

    protected $batch_actions;

    protected $filter_columns;

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
     * Return a list of action from builders.filters.params
     * @return array
     */
    public function getFilterColumns()
    {
        if (0 === count($this->filter_columns)) {
            $this->findFilterColumns();
        }

        return $this->filter_columns;
    }

    protected function addFilterColumn(Column $column)
    {
        $this->filter_columns[$column->getName()] = $column;
    }

    protected function findFilterColumns()
    {
        $filters = $this->getFilters();

        if (!isset($filters['display']) || is_null($filters['display'])) {
            $filters['display'] = $this->getAllFields();
        }

        foreach ($filters['display'] as $columnName) {
            $column = new Column($columnName);
            $column->setDbType($this->getFieldOption($column, 'dbType', $this->getFieldGuesser()->getDbType($this->getVariable('model'), $columnName)));
            $column->setFormType($this->getFieldOption($column, 'filterType', $this->getFieldGuesser()->getFilterType($column->getDbType(), $columnName)));
            $column->setFormOptions($this->getFieldOption($column, 'filterOptions', $this->getFieldGuesser()->getFilterOptions($column->getFormType(), $column->getDbType(), $columnName)));

            //Set the user parameters
            $this->setUserColumnConfiguration($column);
            $this->addFilterColumn($column);
        }

    }

    /**
     * Return a list of action from list.object_actions
     * @return array
     */
    public function getObjectActions()
    {
        if (0 === count($this->object_actions)) {
            $this->findObjectActions();
        }

        return $this->object_actions;
    }

    protected function setUserObjectActionConfiguration(Action $action)
    {
        $options = $this->getVariable(sprintf('object_actions[%s]', $action->getName()),array(), true);

        if (null !== $options) {
            foreach ($options as $option => $value) {
                $action->setOption($option, $value);
            }
        }
    }

    protected function addObjectAction(Action $action)
    {
        $this->object_actions[$action->getName()] = $action;
    }

    protected function findObjectActions()
    {
        foreach ($this->getVariable('object_actions') as $actionName => $actionParams) {
            $class = 'Admingenerator\\GeneratorBundle\\Generator\\'.Container::camelize($actionName.'Action');
            if (class_exists($class)) {
                $action = new $class($actionName, $this);
            } else {
                $action = new Action($actionName);
            }

            $this->setUserObjectActionConfiguration($action);
            $this->addObjectAction($action);
        }
    }

    /**
     * Return a list of batch action from list.batch_actions
     * @return array
     */
    public function getBatchActions()
    {
        if (0 === count($this->batch_actions)) {
            $this->findBatchActions();
        }

        return $this->batch_actions;
    }

    protected function setUserBatchActionConfiguration(Action $action)
    {
        $options = $this->getVariable(sprintf('batch_actions[%s]', $action->getName()),array(), true);

        if (null !== $options) {
            foreach ($options as $option => $value) {
                $action->setOption($option, $value);
            }
        }
    }

    protected function addBatchAction(Action $action)
    {
        $this->batch_actions[$action->getName()] = $action;
    }

    protected function findBatchActions()
    {
        foreach ($this->getVariable('batch_actions', array()) as $actionName => $actionParams) {
            $class = 'Admingenerator\\GeneratorBundle\\Generator\\'.Container::camelize($actionName.'BatchAction');
            if (class_exists($class)) {
                $action = new $class($actionName, $this);
            } else {
                $action = new Action($actionName);
            }

            $this->setUserBatchActionConfiguration($action);
            $this->addBatchAction($action);
        }
    }

}
