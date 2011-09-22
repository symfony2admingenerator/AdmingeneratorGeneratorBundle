<?php

namespace Admingenerator\GeneratorBundle\Tests;

/*
 * @author Cedric LOMBARDOT
 */
class TestCase extends \PHPUnit_Framework_TestCase
{

    protected $_container;

    protected function initContainer()
    {
        $kernel = new \AppKernel("test", true);
        $kernel->boot();
        $this->_container = $kernel->getContainer();
    }

    protected function getContainer()
    {
        if (!$this->_container) {
            $this->initContainer();
        }

        return $this->_container;
    }

}
