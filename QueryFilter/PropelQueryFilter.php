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

    public function addDateFilter($field, $value)
    {
        if (is_array($value)) {
            $filters = array();

            if ($value['from']) {
                $filters['min'] = $value['from']->format('Y-m-d');
            }

            if ($value['to']) {
                $filters['max'] = $value['to']->format('Y-m-d');
            }

            if (count($filters) > 0) {
                $method = 'filterBy'.Inflector::classify($field);
                $this->query->$method($filters);
            }

        } elseif ($value instanceof \DateTime) {
            $this->query->filterBy($field, $value->format('Y-m-d'));
        }
    }

    public function addTimestampFilter($field, $value)
    {
        return $this->addDateFilter($field, $value);
    }
}
