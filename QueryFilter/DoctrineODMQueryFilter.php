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

    public function addDateFilter($field, $value)
    {
        if (is_array($value)) {
            if ($value['from'] && !$value['to']) {
                $this->query->field($field)->lte($value['from']);
            }

            if ($value['to'] && !$value['from']) {
                $this->query->field($field)->gte($value['to']);
            }

            if ($value['to'] && $value['from']) {
                $this->query->field($field)->range($value['from'], $value['to']);
            }

        } elseif ($value instanceof \DateTime) {
            $this->query->field($field)->equals($value);
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
