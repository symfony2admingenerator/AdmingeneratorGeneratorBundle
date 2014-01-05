<?php

namespace Admingenerator\GeneratorBundle\QueryFilter;

use  Doctrine\Common\Util\Inflector;

class PropelQueryFilter extends BaseQueryFilter
{

    public function addDefaultFilter($field, $value)
    {
        $method = 'filterBy'.Inflector::classify($field);
        $this->query->$method($value);
    }

    public function addBooleanFilter($field, $value)
    {
        if ("" !== $value) {
            $this->addDefaultFilter($field, $value);
        }
    }

    public function addVarcharFilter($field, $value)
    {
        $this->addDefaultFilter($field, '%'.$value.'%');
    }

    public function addCollectionFilter($field, $value)
    {
        if (!is_array($value)) {
            $value = array($value->getId());
        }

        if (strstr($field, '.')) {
            list($table, $field) = explode('.', $field);
        } else {
            $table = $field;
            $field = 'id';
        }

        $subquery = call_user_func_array(array($this->query, 'use'.$table.'Query'), array($table, \Criteria::INNER_JOIN));
        $subquery->filterBy($field, $value, \Criteria::IN)
                 ->endUse()
                 ->groupById();
    }

    public function addDateFilter($field, $value, $format = 'Y-m-d')
    {
        if (is_array($value)) {
            $filters = array();

            if (array_key_exists('from', $value) && $from = $this->formatDate($value['from'], $format)) {
                $filters['min'] = $from;
            }

            if (array_key_exists('to', $value) && $to = $this->formatDate($value['to'], $format)) {
                $filters['max'] = $to;
            }

            if (count($filters) > 0) {
                $method = 'filterBy'.Inflector::classify($field);
                $this->query->$method($filters);
            }

        } else {
            if (false !== $date = $this->formatDate($value, $format)) {
                $this->query->filterBy($field, $date);
            }
        }
    }

    public function addTimestampFilter($field, $value)
    {
        return $this->addDateFilter($field, $value);
    }
}
