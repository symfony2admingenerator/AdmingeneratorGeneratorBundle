<?php

namespace Admingenerator\GeneratorBundle\Tests\ClassLoader;

use Admingenerator\GeneratorBundle\Tests\TestCase;

use Admingenerator\GeneratorBundle\ClassLoader\AdmingeneratedClassLoader;

class AdmingeneratedClassLoaderTest extends TestCase
{
    /**
     * @dataProvider getLoadClassTests
     */
    public function testLoadClass($className, $testClassName, $message)
    {
        $loader = new AdmingeneratedClassLoader();
        $loader->setBasePath(realpath(sys_get_temp_dir()));
        $loader->loadClass($testClassName);
        $this->assertTrue(class_exists($className), $message);
    }

    public function getLoadClassTests()
    {
        return array(
            array('\\Admingenerated\\AdmingeneratorDemoBundle\\BaseController\\ListController', 'Admingenerated\\AdmingeneratorDemoBundle\\BaseController\\ListController',   '->loadClass() loads admingenerated class'),
        );
    }
}
