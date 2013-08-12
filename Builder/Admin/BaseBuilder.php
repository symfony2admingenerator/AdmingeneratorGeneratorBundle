<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

use Symfony\Component\DependencyInjection\Container;
use Admingenerator\GeneratorBundle\Builder\BaseBuilder as GenericBaseBuilder;
use Admingenerator\GeneratorBundle\Generator\Column;
use Admingenerator\GeneratorBundle\Generator\Action;

/**
 * Base builder generating php for actions
 *
 * @author cedric Lombardot
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class BaseBuilder extends GenericBaseBuilder
{
    protected $columns;

    protected $actions;

    protected $objectActions = array();

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

            $column->setDbType(
                $this->getFieldOption(
                    $column,
                    'dbType',
                    $this->getFieldGuesser()->getDbType(
                        $this->getVariable('model'),
                        $columnName
                    )
                )
            );

            $column->setSortType($this->getFieldGuesser()->getSortType($column->getDbType()));

            if ($this->getYamlKey() != 'list' && $this->getYamlKey() != 'nested_list') {

                $column->setFormType(
                    $this->getFieldOption(
                        $column,
                        'formType',
                        $this->getFieldGuesser()->getFormType(
                            $column->getDbType(),
                            $columnName
                        )
                    )
                );

                $column->setFormOptions(
                    $this->getFieldOption(
                        $column,
                        'formOptions',
                        $this->getFieldGuesser()->getFormOptions(
                            $column->getFormType(),
                            $column->getDbType(),
                            $columnName
                        )
                    )
                );
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
        $options = $this->getVariable(
            sprintf('fields[%s]', $column->getName()),
            array(),
            true
        );

        return isset($options[$optionName]) ? $options[$optionName] : $default;
    }

    protected function setUserColumnConfiguration(Column $column)
    {
        $options = $this->getVariable(
            sprintf('fields[%s]', $column->getName()),
            array(),
            true
        );

        foreach ($options as $option => $value) {
            $column->setProperty($option, $value);
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

        // tabs
        if (null == $display || 0 == sizeof($display)) {
            $tabs = $this->getVariable('tabs');

            if (null != $tabs || 0 < sizeof($tabs)) {
                $display = array();

                foreach ($tabs as $tab) {
                    $display = array_merge($display, $tab);
                }
            }
        }

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
     * @return array
     */
    public function getFieldsets()
    {
        $display = $this->getVariable('display');

        // tabs
        if (null == $display || 0 == sizeof($display)) {
            $tabs = $this->getVariable('tabs');

            if (null != $tabs || 0 < sizeof($tabs)) {
                $display = array();

                foreach ($tabs as $tab) {
                    $display = array_merge($display, $tab);
                }
            }
        }

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
        $builderOptions = $this->getVariable(
            sprintf('actions[%s]', $action->getName()),
            array(),
            true
        );

        $globalOptions = $this->getGenerator()->getFromYaml(
            'params.actions.'.$action->getName(),
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

    protected function addAction(Action $action)
    {
        $this->actions[$action->getName()] = $action;
    }

    protected function findActions()
    {
        foreach ($this->getVariable('actions', array()) as $actionName => $actionParams) {
            $action = $this->findGenericAction($actionName);

            if (!$action) {
                $action = new Action($actionName);
            }

            if ($globalCredentials = $this->getGenerator()->getFromYaml('params.credentials')) {
                // If generator is globally protected by credentials
                // actions are also protected
                $action->setCredentials($globalCredentials);
            }

            $this->setUserActionConfiguration($action);
            $this->addAction($action);
        }
    }

    /**
     * Return a list of action from list.object_actions
     * @return array
     */
    public function getObjectActions()
    {
        if (0 === count($this->objectActions)) {
            $this->findObjectActions();
        }

        return $this->objectActions;
    }

    protected function setUserObjectActionConfiguration(Action $action)
    {
        $builderOptions = $this->getVariable(
                sprintf('object_actions[%s]', $action->getName()),
                array(), true
        );

        $globalOptions = $this->getGenerator()->getFromYaml(
                'params.object_actions.'.$action->getName(), array()
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

    protected function addObjectAction(Action $action)
    {
        $this->objectActions[$action->getName()] = $action;
    }

    protected function findObjectActions()
    {
        $objectActions = $this->getVariable('object_actions', array());

        foreach ($objectActions as $actionName => $actionParams) {
            $action = $this->findObjectAction($actionName);
            if(!$action) {
                $action = new Action($actionName);
            }

            if ($globalCredentials = $this->getGenerator()->getFromYaml('params.credentials')) {
                // If generator is globally protected by credentials
                // object actions are also protected
                $action->setCredentials($globalCredentials);
            }

            $this->setUserObjectActionConfiguration($action);
            $this->addObjectAction($action);
        }
    }

    public function findGenericAction($actionName)
    {
        $class = 'Admingenerator\\GeneratorBundle\\Generator\\Action\\Generic\\'
                .Container::camelize(str_replace('-', '_', $actionName) . 'Action');

        return (class_exists($class)) ? new $class($actionName, $this) : false;
    }

    public function findObjectAction($actionName)
    {
        $class = 'Admingenerator\\GeneratorBundle\\Generator\\Action\\Object\\'
                .Container::camelize(str_replace('-', '_', $actionName) . 'Action');

        return (class_exists($class)) ? new $class($actionName, $this) : false;
    }

    public function findBatchAction($actionName)
    {
        $class = 'Admingenerator\\GeneratorBundle\\Generator\\Action\\Batch\\'
                .Container::camelize(str_replace('-', '_', $actionName) . 'Action');

        return (class_exists($class)) ? new $class($actionName, $this) : false;
    }

    /**
     * Parse a little template with twig for yaml options
     * From @sescandell: is this function still used????
     */
    public function parseStringWithTwig($template, $options = array())
    {
        $loader = new \Twig_Loader_String();
        $twig = new \Twig_Environment(
            $loader,
            array(
                'autoescape' => false,
                'strict_variables' => true,
                'debug' => true,
                'cache' => $this->getGenerator()->getTempDir(),
            )
        );
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
        return $this->getVariable('namespace_prefix')
               .($this->hasVariable('subfolder') ? '\\'.$this->getVariable('subfolder') : '');
    }

    public function getRoutePrefixWithSubfolder()
    {
        return str_replace('\\', '_', $this->getVariable('namespace_prefix'))
               .($this->hasVariable('subfolder') ? '_'.$this->getVariable('subfolder') : '');
    }

    public function getNamespacePrefixForTemplate()
    {
        return str_replace('\\', '', $this->getVariable('namespace_prefix'));
    }

    public function getBaseActionsRoute()
    {
        return str_replace(
            '\\',
            '_',
            $this->getVariable('namespace_prefix')
            .'_'.$this->getVariable('bundle_name')
            .'_'.$this->getBaseGeneratorName()
        );
    }

    public function getObjectActionsRoute()
    {
        return $this->getBaseActionsRoute().'_object';
    }

    /**
     * Get the PK column name
     *
     * @return string parameter
     */
    public function getModelPrimaryKeyName()
    {
        return $this->getGenerator()->getFieldGuesser()->getModelPrimaryKeyName();
    }

    /**
     * Allow to add complementary strylesheets
     *
     *
     * param:
     *   stylesheets:
     *     - path/css.css
     *     - { path: path/css.css, media: all }
     *
     * @return array
     */
    public function getStylesheets()
    {
        $parse_stylesheets = function ($params, $stylesheets) {
            foreach ($params as $css) {
                if (is_string($css)) {
                    $css = array(
                        'path'  => $css,
                        'media' => 'all',
                    );
                }

                $stylesheets[] = $css;
            }

            return $stylesheets;
        };

        // From config.yml
        $stylesheets = $parse_stylesheets(
            $this->getGenerator()->getContainer()
                 ->getParameter('admingenerator.stylesheets', array()), array()
        );

        // From generator.yml
        $stylesheets = $parse_stylesheets(
            $this->getVariable('stylesheets', array()), $stylesheets
        );

        return $stylesheets;
    }

    /**
     * Allow to add complementary javascripts
     *
     *
     * param:
     *   javascripts:
     *     - path/js.js
     *     - { path: path/js.js }
     *     - { route: my_route, routeparams: {} }
     *
     * @return array
     */
    public function getJavascripts()
    {
        $self = $this;
        $parse_javascripts = function ($params, $javascripts) use ($self) {
            foreach ($params as $js) {

                if (is_string($js)) {
                    $js = array(
                        'path'  => $js,
                    );
                } elseif (isset($js['route'])) {
                    $js = array(
                        'path'  => $self->getGenerator()
                                        ->getContainer()
                                        ->get('router')
                                        ->generate($js['route'], $js['routeparams'])
                    );
                }

                $javascripts[] = $js;
            }

            return $javascripts;
        };

        // From config.yml
        $javascripts = $parse_javascripts(
            $this->getGenerator()->getContainer()
                 ->getParameter('admingenerator.javascripts', array()), array()
        );

        // From generator.yml
        $javascripts = $parse_javascripts(
            $this->getVariable('javascripts', array()), $javascripts
        );

        return $javascripts;
    }
}
