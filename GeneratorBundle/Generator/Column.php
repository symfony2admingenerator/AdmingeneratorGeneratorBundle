<?php

namespace Admingenerator\GeneratorBundle\Generator;

/**
 *
 * This class describe a column
 * @author cedric Lombardot
 *
 */
class Column
{
    protected $name;
    
    protected $sort_on;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function isSortable()
    {
        return $this->isReal() || $this->sort_on != "";
    }
    
    public function isReal()
    {
        return true;
    }
    
    public function getSortOn()
    {
        return $this->sort_on != "" ? $this->sort_on : $this->name;
    }
    
    public function setSortOn($sort_on)
    {
        return $this->sort_on = $sort_on;
    }
    
}