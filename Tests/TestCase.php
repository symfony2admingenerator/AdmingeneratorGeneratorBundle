<?php

namespace Admingenerator\GeneratorBundle\Tests;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/*
 * @author Cedric LOMBARDOT
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function getContainer()
    {
        return new ContainerBuilder(new ParameterBag(array(
            'kernel.debug' => false,
        )));
    }
}
