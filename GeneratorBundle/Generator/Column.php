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

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

}