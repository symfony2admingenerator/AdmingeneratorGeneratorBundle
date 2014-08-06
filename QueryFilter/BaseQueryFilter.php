<?php

namespace Admingenerator\GeneratorBundle\QueryFilter;

abstract class BaseQueryFilter implements QueryFilterInterface
{
    protected $query;

    protected $aliasPrefix = 'qf_';

    protected $namePrefix = 'query_filter_uniq_';

    protected $aliasCount = 0;

    protected $nameCount = 0;

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
     * @see GeneratorBundle\QueryFilter.QueryFilterInterface::setAliasPrefix()
     */
    public function setAliasPrefix($aliasPrefix)
    {
        $this->aliasPrefix = $aliasPrefix;
    }

    /**
     * (non-PHPdoc)
     * @see GeneratorBundle\QueryFilter.QueryFilterInterface::setNamePrefix()
     */
    public function setNamePrefix($namePrefix)
    {
        $this->namePrefix = $namePrefix;
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
     * @see GeneratorBundle\QueryFilter.QueryFilterInterface::getUniqueAlias()
     */
    public function getUniqueAlias()
    {
        return $this->aliasPrefix.$this->aliasCount++;
    }

    /**
     * (non-PHPdoc)
     * @see GeneratorBundle\QueryFilter.QueryFilterInterface::getUniqueName()
     */
    public function getUniqueName()
    {
        return $this->namePrefix.$this->nameCount++;
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
