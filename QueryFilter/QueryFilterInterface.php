<?php

namespace Admingenerator\GeneratorBundle\QueryFilter;

interface QueryFilterInterface
{
    /**
     * @param object $query The query object interface depend of the ORM.
     *
     * @api
     */
    public function setQuery($query);

    /**
     * @return object $query The query object interface depend of the ORM.
     *
     * @api
     */
    public function getQuery();

    /**
     * @param string $prefix Prefix used by QueryFilter for parameter name generation.
     *
     * @api
     */
    public function setPrefix($prefix);

    /**
     * @param array $filtersMap An array containing field to filter map.
     *
     * @api
     */
    public function setFiltersMap(array $filtersMap);

    /**
     * @param array $primaryKeysMap An array containing field to primary key map.
     *
     * @api
     */
    public function setPrimaryKeysMap(array $primaryKeysMap);

    /**
     * @return string The unique parameter name.
     *
     * @api
     */
    public function getParamName();

    /**
     * @param mixed  $value     The value to format.
     * @param string $operator  The comparison operator.
     * @param string $field     The field name.
     * 
     * @return string The formatted value.
     *
     * @api
     */
    public function formatValue($value, $operator, $field);
}
