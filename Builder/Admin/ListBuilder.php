<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

use Admingenerator\GeneratorBundle\Generator\Column;
use Admingenerator\GeneratorBundle\Generator\Action;

/**
 * This builder generates php for list actions
 *
 * @author cedric Lombardot
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class ListBuilder extends BaseBuilder
{
    protected $batch_actions;

    protected $filter_columns;

    protected $scope_columns;

    /**
     * (non-PHPdoc)
     * @see Admingenerator\GeneratorBundle\Builder.BaseBuilder::getYamlKey()
     */
    public function getYamlKey()
    {
        return 'list';
    }

    /**
     * Find filters parameters
     */
    public function getFilters()
    {
        return $this->getGenerator()->getFromYaml('builders.filters.params');
    }

    public function getFilterColumns()
    {
        if (0 === count($this->filter_columns)) {
            $this->findFilterColumns();
        }

        return $this->filter_columns;
    }

    protected function findFilterColumns()
    {
        foreach ($this->getFiltersDisplayColumns() as $columnName) {
            $column = $this->createColumn($columnName);

            // Set the user parameters
            $this->setUserColumnConfiguration($column);
            $this->addFilterColumn($column);
        }
    }

    /**
     * @return array Filters display column names
     */
    protected function getFiltersDisplayColumns()
    {
        $display = $this->getGenerator()->getFromYaml('builders.filters.params.display', array());

        if (null === $display) {
            $display = $this->getAllFields();
        }

        return $display;
    }

    protected function addFilterColumn(Column $column)
    {
        $this->filter_columns[$column->getName()] = $column;
    }

    /**
     * Find scopes parameters
     */
    public function getScopes()
    {
        return $this->getGenerator()->getFromYaml('builders.list.params.scopes');
    }

    /**
     * @return array
     */
    public function getScopeColumns()
    {
        if (0 === count($this->scope_columns)) {
            $this->findScopeColumns();
        }

        return $this->scope_columns;
    }

    protected function findScopeColumns()
    {
        foreach ($this->getScopesDisplayColumns() as $columnName) {
            $column = $this->createColumn($columnName);

            // Set the user parameters
            $this->setUserColumnConfiguration($column);
            $this->addScopeColumn($column);
        }
    }

    /**
     * @return array Scopes display column names
     */
    protected function getScopesDisplayColumns()
    {
        $scopeGroups = $this->getGenerator()->getFromYaml('builders.list.params.scopes', array());
        $scopeColumns = array();

        foreach ($scopeGroups as $scopeGroup) {
            foreach ($scopeGroup as $scopeFilter) {
                if (array_key_exists('filters', $scopeFilter) && is_array($scopeFilter['filters'])) {
                    foreach ($scopeFilter['filters'] as $field => $value) {
                        $scopeColumns[] = $field;
                    }
                }
            }
        }

        return $scopeColumns;
    }

    protected function addScopeColumn(Column $column)
    {
        $this->scope_columns[$column->getName()] = $column;
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
        $builderOptions = $this->getVariable(
            sprintf('batch_actions[%s]', $action->getName()),
            array(),
            true
        );

        $globalOptions = $this->getGenerator()->getFromYaml(
            'params.batch_actions.'.$action->getName(),
            array()
        );

        if (null !== $builderOptions) {
            foreach ($builderOptions as $option => $value) {
                $action->setProperty($option, $value);
            }
        } elseif (null !== $globalOptions) {
            foreach ($globalOptions as $option => $value) {
                $action->setProperty($option, $value);
            }
        }
    }

    protected function addBatchAction(Action $action)
    {
        $this->batch_actions[$action->getName()] = $action;
    }

    protected function findBatchActions()
    {
        $batchActions = $this->getVariable('batch_actions', array());

        foreach ($batchActions as $actionName => $actionParams) {
            $action = $this->findBatchAction($actionName);
            
            if (!$action) {
                $action = new Action($actionName);
            }

            if ($globalCredentials = $this->getGenerator()->getFromYaml('params.credentials')) {
                // If generator is globally protected by credentials
                // batch actions are also protected
                $action->setCredentials($globalCredentials);
            }

            $this->setUserBatchActionConfiguration($action);
            $this->addBatchAction($action);
        }
    }
}
