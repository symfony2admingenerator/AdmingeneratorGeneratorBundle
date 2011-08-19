<?php

namespace Admingenerator\GeneratorBundle\QueryFilter;

class DoctrineQueryFilter extends BaseQueryFilter
{
    
    public function addDefaultFilter($field, $value)
    {
        $this->query->andWhere(sprintf('q.%s = :%s',$field, $field));
        $this->query->setParameter($field, $value);
    }
    
    public function addStringFilter($field, $value)
    {
        $this->query->andWhere(sprintf('q.%s LIKE :%s',$field, $field));
        $this->query->setParameter($field, '%'.$value.'%');
    }
}