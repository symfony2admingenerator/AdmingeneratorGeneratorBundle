<?php

namespace Admingenerator\GeneratorBundle\QueryFilter;

abstract class BaseQueryFilter implements QueryFilterInterface
{
    protected $query;

    protected $prefix = 'query_filter_param_';

    protected $count = 0;

    protected $filtersMap = array();

    protected $primaryKeysMap = array();

    /**
     * (non-PHPdoc)
     * @see GeneratorBundle\QueryFilter.QueryFilterInterface::setQuery()
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * (non-PHPdoc)
     * @see GeneratorBundle\QueryFilter.QueryFilterInterface::getQuery()
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * (non-PHPdoc)
     * @see GeneratorBundle\QueryFilter.QueryFilterInterface::setPrefix()
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * (non-PHPdoc)
     * @see GeneratorBundle\QueryFilter.QueryFilterInterface::setFiltersMap()
     */
    public function setFiltersMap(array $filtersMap)
    {
        $this->filtersMap = $filtersMap;
    }

    /**
     * (non-PHPdoc)
     * @see GeneratorBundle\QueryFilter.QueryFilterInterface::setPrimaryKeysMap()
     */
    public function setPrimaryKeysMap(array $primaryKeysMap)
    {
        $this->primaryKeysMap = $primaryKeysMap;
    }

    /**
     * (non-PHPdoc)
     * @see GeneratorBundle\QueryFilter.QueryFilterInterface::getParamName()
     */
    public function getParamName()
    {
        return $this->prefix.$this->count++;
    }

    /**
     * (non-PHPdoc)
     * @see GeneratorBundle\QueryFilter.QueryFilterInterface::formatValue()
     */
    public function formatValue($value, $operator, $field)
    {
        throw new \LogicException('Not implemented.');
    }

    /**
     * Format date.
     * 
     * @param  mixed    $date   The date to format.
     * @param  string   $format The format.
     * 
     * @return string The formatted date.
     */
    protected function formatDate($date, $format)
    {
        if (!($date instanceof \DateTime)) {
            $date = new \DateTime($date);
        }

        if (false !== $date) {
            return $date->format($format);
        }

        return $date;
    }

    /**
     * Get filter for field.
     *
     * @param string $field The field name.
     *
     * @return string The filter.
     */
    protected function getFilterFor($field)
    {
        return $this->filtersMap[$field];
    }

    /**
     * Get primary key for field.
     *
     * @param string $field The field name.
     *
     * @return string The primary key.
     */
    protected function getPrimaryKeyFor($field)
    {
        return $this->primaryKeysMap[$field];
    }
}
