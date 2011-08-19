<?php 

namespace Admingenerator\GeneratorBundle\QueryFilter; 


class BaseQueryFilter implements QueryFilterInterface
{
    protected $query;
    
    public function setQuery($query)
    {
        $this->query = $query;
    }
    
    public function getQuery()
    {
        return $this->query;
    }
    
    public function addDefaultFilter($field, $value)
    {
        throw new \LogicException('No method defined to execute this type of filters');
    }
    
    public function __call($name, $values = array())
    {
        if (preg_match('/add(.+)Filter/', $name)) {
            $this->addDefaultFilter($values[0], $values[1]);
        }
    }
}