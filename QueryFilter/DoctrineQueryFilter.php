<?php

namespace Admingenerator\GeneratorBundle\QueryFilter;

class DoctrineQueryFilter extends BaseQueryFilter
{

    public function addDefaultFilter($field, $value)
    {
        $this->query->andWhere(sprintf('q.%s = :%s',$field, $field));
        $this->query->setParameter($field, $value);
    }

    public function addBooleanFilter($field, $value)
    {
        if ("" !== $value) {
            $this->query->andWhere(sprintf('q.%s = :%s',$field, $field));
            $this->query->setParameter($field, $value);
        }
    }

    public function addStringFilter($field, $value)
    {
        $this->query->andWhere(sprintf('q.%s LIKE :%s',$field, $field));
        $this->query->setParameter($field, '%'.$value.'%');
    }
    
    public function addTextFilter($field, $value)
    {
        $this->addStringFilter($field, $value);
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

        $this->query->leftJoin('q.'.$table, $table);
        $this->query->groupBy('q.id');
        $this->query->andWhere(sprintf('%s.%s IN (:%s)',$table, $field, $table.'_'.$field));
        $this->query->setParameter($table.'_'.$field, $value);

    }

    public function addDateFilter($field, $value, $format = 'Y-m-d')
    {
        if (is_array($value)) {
            if ($value['from']) {
                $this->query->andWhere(sprintf('q.%s >= :%s_from',$field, $field ));
                $this->query->setParameter($field.'_from' , $value['from']->format($format));
            }

            if ($value['to']) {
                $this->query->andWhere(sprintf('q.%s <= :%s_to',$field, $field ));
                $this->query->setParameter($field.'_to' , $value['to']->format($format));
            }

        } elseif ($value instanceof \DateTime) {
            $this->query->andWhere(sprintf('q.%s = :%s',$field, $field ));
            $this->query->setParameter($field, $value->format($format));
        }
    }

    public function addDatetimeFilter($field, $value)
    {
        $this->addDateFilter($field, $value, 'Y-m-d H:i:s');
    }
}
