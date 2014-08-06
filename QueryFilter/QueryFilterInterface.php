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
     * @param string $aliasPrefix Prefix used by QueryFilter for unique alias generation.
     *
     * @api
     */
    public function setAliasPrefix($aliasPrefix);

    /**
     * @param string $namePrefix Prefix used by QueryFilter for unique name generation.
     *
     * @api
     */
    public function setNamePrefix($namePrefix);

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
     * @return string The unique alias.
     *
     * @api
     */
    public function getUniqueAlias();

    /**
     * @return string The unique name.
     *
     * @api
     */
    public function getUniqueName();

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
