<?php

namespace Admingenerator\GeneratorBundle\QueryFilter;

class PropelQueryFilter extends BaseQueryFilter
{

    public function addDefaultFilter($field, $value)
    {
        $this->query->filterBy($field, $value);
    }

    public function addBooleanFilter($field, $value)
    {
        if ("" !== $value) {
            $this->query->filterBy($field, $value);
        }
    }

    public function addVarcharFilter($field, $value)
    {
        $this->query->filterBy($field, '%'.$value.'%', \Criteria::LIKE);
    }

    /*
     * @todo convert for propel
     * public function addCollectionFilter($field, $value)
    {
        if (!is_array($value)) {
            $value = array($value->getId());
        }

        if (strstr($field, '.')) {
            list($table, $field) = explode('.', $field);
        } else {
            $table = $field;
            $field = $id;
        }

        $this->query->leftJoin('q.'.$table, $table);
        $this->query->groupBy('q.id');
        $this->query->andWhere(sprintf('%s.%s IN (:%s)',$table, $field, $table.'_'.$field));
        $this->query->setParameter($table.'_'.$field, $value);

    }*/

    public function addDateFilter($field, $value)
    {
        if (is_array($value)) {
            $filters = array();
            
            if ($value['from']) {
                $filters['min'] = $value['from']->format('Y-m-d');
            }

            if ($value['to']) {
                $filters['to'] = $value['to']->format('Y-m-d');
            }

            if (count($filters) > 0) {
                $method = 'filterBy'.$field;
                call_user_func_array(array($this->query, $method), array($filters));
            }  
            
        } elseif($value instanceof \DateTime) {
            $this->query->filterBy($field, $value->format('Y-m-d'));
        }
    }
}
