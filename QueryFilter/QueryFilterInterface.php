<?php 

namespace Admingenerator\GeneratorBundle\QueryFilter;

interface QueryFilterInterface
{
    function setQuery($query);
    
    function getQuery();
    
    function addDefaultFilter($field, $value);
    
    function __call($name, $value = array());
}