<?php

namespace Admingenerator\GeneratorBundle\QueryFilter;

class DoctrineODMQueryFilter extends BaseQueryFilter
{

    public function addDefaultFilter($field, $value)
    {
        $this->query->field($field)->equals($value);
    }

    public function addStringFilter($field, $value)
    {
        $this->query->field($field)->equals(new \MongoRegex("/.*$value.*/i"));
    }

    public function addBooleanFilter($field, $value)
    {
        if ("" !== $value) {
            $this->query->field($field)->equals((boolean) $value);
        }
    }

    public function addDateFilter($field, $value, $format = 'Y-m-d')
    {
        if (is_array($value)) {
            $from = array_key_exists('from', $value) ? $this->formatDate($value['from'], $format) : false;
            $to   = array_key_exists('to',   $value) ? $this->formatDate($value['to'],   $format) : false;

            if ($to && $from) {
                $this->query->field($field)->range($from, $to);
            } elseif ($from) {
                $this->query->field($field)->gte($from);
            } elseif ($to) {
                $this->query->field($field)->lte($to);
            }
        } else {
            if (false !== $date = $this->formatDate($value, $format)) {
                $this->query->field($field)->equals($date);
            }
        }
    }

    public function addDocumentFilter($field, $value)
    {
         $this->query->field($field.'.$id')->equals(new \MongoId($value->getId()));
    }

    public function addCollectionFilter($field, $value)
    {
         $this->query->field($field.'.$id')->equals(new \MongoId($value->getId()));
    }
}
