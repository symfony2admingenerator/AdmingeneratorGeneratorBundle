<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

use Admingenerator\GeneratorBundle\Generator\Column;

/**
 * This builder generates php for filters
 * @author cedric Lombardot
 */
class FiltersBuilder extends BaseBuilder
{
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
        $display = $this->getVariable('display');

        if (null == $display) {
            $display = $this->getAllFields();
        }

        foreach ($display as $columnName) {
            $column = new Column($columnName);

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

            $column->setFormType(
                $this->getFieldGuesser()->getFilterType(
                    $column->getDbType(),
                    $columnName
                )
            );

            $column->setFormOptions(
                $this->getFieldGuesser()->getFilterOptions(
                    $column->getFormType(),
                    $column->getDbType(),
                    $columnName
                )
            );

            //Set the user parameters
            $this->setUserColumnConfiguration($column);

            $this->addColumn($column);
        }
    }
}
