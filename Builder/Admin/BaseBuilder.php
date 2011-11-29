<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

use Admingenerator\GeneratorBundle\Builder\BaseBuilder as GenericBaseBuilder;

use Admingenerator\GeneratorBundle\Generator\Column;

use Admingenerator\GeneratorBundle\Generator\Action;

class BaseBuilder extends GenericBaseBuilder
{

    protected $columns;

    protected $actions;

    protected $columnClass = 'Column';

    public function getBaseAdminTemplate()
    {
        return $this->getGenerator()->getBaseAdminTemplate();
    }

    /**
     * Return a list of columns from list.display
     * @return array
     */
    public function getColumns()
    {
        if (0 === count($this->columns)) {
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
            $column = new $this->columnClass($columnName);
            $column->setDbType($this->getFieldOption($column, 'dbType', $this->getFieldGuesser()->getDbType($this->getVariable('model'), $columnName)));

            if ($this->getYamlKey() != 'list') {
              $column->setFormType($this->getFieldOption($column, 'formType', $this->getFieldGuesser()->getFormType($column->getDbType(), $columnName)));
              $column->setFormOptions($this->getFieldOption($column, 'formOptions', $this->getFieldGuesser()->getFormOptions($column->getFormType(), $column->getDbType(), $columnName)));
            }
            //Set the user parameters
            $this->setUserColumnConfiguration($column);

            $this->addColumn($column);
        }
    }

    protected function getColumnClass()
    {
        return $this->columnClass;
    }

    public function setColumnClass($columnClass)
    {
        return $this->columnClass = $columnClass;
    }

    protected function getFieldOption(Column $column, $optionName, $default = null)
    {
        $options = $this->getVariable(sprintf('fields[%s]', $column->getName()),array(), true);

        return isset($options[$optionName]) ? $options[$optionName] : $default;
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

        if (null == $display || 0 == sizeof($display)) {
           return $this->getAllFields();
        }

        if (isset($display[0])) {
            return $display;
        }

        //there is fieldsets
        $return = array();

        foreach ($display as $fieldset => $rows_or_fields) {
            foreach ($rows_or_fields as $fields) {
                if (is_array($fields)) { //It s a row
                    $return = array_merge($return, $fields);
                } else {
                    $return[$fields] = $fields;
                }
            }
        }

        return $return;
    }

    /**
     * Retrieve all columns
     *
     * @return array
     */
    protected function getAllFields()
    {
        return $this->getFieldGuesser()->getAllFields($this->getVariable('model'));
    }

    /**
     * @return array(
     *
     * )
     *
     */
    public function getFieldsets()
    {
        $display = $this->getVariable('display');

        if (null == $display || 0 == sizeof($display)) {
           $display = $this->getAllFields();
        }

        if (isset($display[0])) {
            $display = array('NONE' => $display);
        }

        foreach ($display as $fieldset => $rows_or_fields) {
            $display[$fieldset] = $this->getRowsFromFieldset($rows_or_fields);
        }

        return $display;
    }

    protected function getRowsFromFieldset(array $rows_or_fields)
    {
        $rows = array();

        foreach ($rows_or_fields as $field) {
            if (is_array($field)) { //The row is defined in yaml
                $rows[] = array_combine($field, $field);
            } else {
                $rows[][$field] = $field;
            }
        }

        return $rows;
    }

    /**
     * Return a list of action from list.actions
     * @return array
     */
    public function getActions()
    {
        if (0 === count($this->actions)) {
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

    public function getBaseGeneratorName()
    {
        return $this->getGenerator()->getBaseGeneratorName();
    }

    public function getNamespacePrefixWithSubfolder()
    {
        return $this->getVariable('namespace_prefix') . ($this->hasVariable('subfolder') ? '\\' . $this->getVariable('subfolder') : '');
    }

    public function getRoutePrefixWithSubfolder()
    {
        return $this->getVariable('namespace_prefix') . ($this->hasVariable('subfolder') ? '_' . $this->getVariable('subfolder') : '');
    }
}
