<?php

namespace Admingenerator\GeneratorBundle\Exception;

class ClassNotFoundException extends \LogicException
{
    public function __construct($class)
    {
        parent::__construct('Ups, maybe error typo in your generator.yml, the class '.$class.' not found');

    }
}
