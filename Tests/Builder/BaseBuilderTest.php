<?php

namespace Admingenerator\GeneratorBundle\Tests\Builder;

use Admingenerator\GeneratorBundle\Builder\Admin\BaseBuilder;

use Admingenerator\GeneratorBundle\Tests\TestCase;

class BaseBuilderTest extends TestCase
{
    public function testGetSimpleClassName()
    {
        $builder = new BaseBuilder();
        $this->assertEquals('BaseBuilder', $builder->getSimpleClassName(), 'getSimpleClassName remove the namespaced part of get_class');

        $this->assertEquals('Bar', $builder->getSimpleClassName('\\Foo\\Bar'), 'getSimpleClassName remove the namespaced part of get_class');
    }

    public function testGetDefaultTemplateName()
    {
        $builder = new BaseBuilder();
        $this->assertEquals('BaseBuilder.php.twig', $builder->getDefaultTemplateName(), 'getDefaultTemplateName return the twig file path');
    }

    public function testSetVariables()
    {
        $builder = new BaseBuilder();
        $builder->setVariables(array('foo' => 'bar'));
        $this->assertEquals(array('foo' => 'bar'), $builder->getVariables(), 'setVariables accept an array');
    }

    public function testGetVariable()
    {
        $builder = new BaseBuilder();
        $builder->setVariables(array('foo' => 'bar'));
        $this->assertEquals('bar', $builder->getVariable('foo','default'));
        $this->assertEquals('default', $builder->getVariable('nonexistant','default'));

        $builder->setVariables(array('foo' => array('bar' =>'bazz')));
        $this->assertEquals('bazz', $builder->getVariable('foo[bar]','default', true));
    }

    public function testHasVariable()
    {
        $builder = new BaseBuilder();
        $builder->setVariables(array('foo' => 'bar'));
        $this->assertTrue($builder->hasVariable('foo'), 'hasVariable return true on a valid key');
        $this->assertFalse($builder->hasVariable('var'), 'hasVariable return false on a invalid key');
    }

    public function testGetCode()
    {
        $builder = $this->initBuilder();

        $this->assertEquals('Hello cedric!'."\n", $builder->getCode());

        $builder->setVariables(array('name' => 'Tux'));
        $this->assertEquals('Hello Tux!'."\n", $builder->getCode(), 'If I change variables code is changed');
    }

    public function testWriteOnDisk()
    {
        $builder = $this->initBuilder();

        $builder->writeOnDisk(sys_get_temp_dir());
        $this->assertTrue(file_exists(sys_get_temp_dir() . '/test.php'));
        $this->assertEquals('Hello cedric!'."\n", file_get_contents(sys_get_temp_dir() . '/test.php'));

        $builder->setVariables(array('name' => 'Tux'));
        $builder->writeOnDisk(sys_get_temp_dir());
        $this->assertTrue($builder->mustOverwriteIfExists());
        $this->assertTrue(file_exists(sys_get_temp_dir() . '/test.php'));
        $this->assertEquals('Hello Tux!'."\n", file_get_contents(sys_get_temp_dir() . '/test.php'), 'If i change variables code is changed');

        $builder->setVariables(array('name' => 'cedric'));
        $builder->setMustOverwriteIfExists(false);
        $builder->writeOnDisk(sys_get_temp_dir());
        $this->assertFalse($builder->mustOverwriteIfExists());
        $this->assertTrue(file_exists(sys_get_temp_dir() . '/test.php'));
        $this->assertEquals('Hello Tux!'."\n", file_get_contents(sys_get_temp_dir() . '/test.php'), 'If i change variables on an existant files code is not generated');

        unlink(sys_get_temp_dir() . '/test.php');
        $this->assertFalse(file_exists(sys_get_temp_dir() . '/test.php'));
        $builder->writeOnDisk(sys_get_temp_dir());
        $this->assertEquals('Hello cedric!'."\n", file_get_contents(sys_get_temp_dir() . '/test.php'), 'If i change variables on a non existant files code is generated');
    }

    public function testGetModelClass()
    {
        $builder = new BaseBuilder();
        $builder->setVariables(array('model' => 'Admingenerator\DemoBundle\Entity\Movie'));
        $this->assertEquals('Movie', $builder->getModelClass());
    }

    protected function initBuilder()
    {
        $builder = new BaseBuilder();
        $generator = $this->getMockBuilder('Admingenerator\GeneratorBundle\Builder\Generator')
                          ->disableOriginalConstructor()
                          ->getMock();

        $builder->setGenerator($generator);
        $builder->setMustOverwriteIfExists(true);
        $builder->setOutputName('test.php');
        $builder->setTemplateDirs(array(__DIR__.'/Fixtures/'));
        $builder->setVariables(array('name' => 'cedric'));
        $builder->setTemplateName($builder->getDefaultTemplateName());

        return $builder;
    }

}
