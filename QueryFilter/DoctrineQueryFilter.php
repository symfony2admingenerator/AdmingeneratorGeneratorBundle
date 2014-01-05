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
            $this->query->setParameter($field, !!$value);
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
        $this->query->groupBy('q');
        $this->query->andWhere(sprintf('%s.%s IN (:%s)',$table, $field, $table.'_'.$field));
        $this->query->setParameter($table.'_'.$field, $value);

    }

    public function addDateFilter($field, $value, $format = 'Y-m-d')
    {
        if (is_array($value)) {
            if (array_key_exists('from', $value)) {
                if (false !== $from = $this->formatDate($value['from'], $format)) {
                    $this->query->andWhere(sprintf('q.%s >= :%s_from',$field, $field ));
                    $this->query->setParameter($field.'_from' , $from);
                }
            }

            if (array_key_exists('to', $value)) {
                if (false !== $to = $this->formatDate($value['to'], $format)) {
                    $this->query->andWhere(sprintf('q.%s <= :%s_to',$field, $field ));
                    $this->query->setParameter($field.'_to' , $to);
                }
            }

        } else {
            if (false !== $date = $this->formatDate($value, $format)) {
                $this->query->andWhere(sprintf('q.%s = :%s',$field, $field ));
                $this->query->setParameter($field, $date);
            }
        }
    }

    public function addDatetimeFilter($field, $value, $format = 'Y-m-d H:i:s')
    {
        $this->addDateFilter($field, $value, $format);
    }
}
