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

    /**
     * @return array Display column names
     */
    protected function getDisplayColumns()
    {
        $display = $this->getVariable('display');

        if (null === $display) {
            $display = $this->getAllFields();
        }

        return $display;
    }
}
