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
            $this->query->field($field)->equals($value);
        }
    }
    
    public function addDateFilter($field, $value)
    {
        if (is_array($value)) {
            if ($value['from'] && !$value['to']) {
                $this->query->field($field)->lte($value['from']->format('Y-m-d'));
            }

            if ($value['to'] && !$value['from']) {
                $this->query->field($field)->gte($value['to']->format('Y-m-d'));
            }

            if ($value['to'] && $value['from']) {
                $this->query->field($field)->range($value['from']->format('Y-m-d'), $value['to']->format('Y-m-d'));
            }
             
        } elseif($value instanceof \DateTime) {
            $this->query->field($field)->equals($value->format('Y-m-d'));
        }
    }

    
}
