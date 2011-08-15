<?php 

namespace Admingenerator\GeneratorBundle\Generator;

/**
 *
 * This class describe an action
 * @author cedric Lombardot
 *
 */
class Action
{
    
    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @todo implement optionnal parameters
     */
    public function getRoute()
    {
        return false;
    }
}