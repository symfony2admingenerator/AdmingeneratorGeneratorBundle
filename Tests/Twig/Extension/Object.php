<?php

namespace Admingenerator\GeneratorBundle\Tests\Twig\Extension;

/**
 * Dummy object for EchoExtensionTest
 *
 * @author Cedric LOMBARDOT
 */
class Object
{
    public static $called = array(
        '__toString'  => 0,
        'foo'         => 0,
        'getFooBar'   => 0,
    );

    public function __construct($bar = 'bar')
    {

    }

    public static function reset()
    {
        self::$called = array(
            '__toString'  => 0,
            'foo'         => 0,
            'getFooBar'   => 0,
        );
    }

    public function __toString()
    {
        ++self::$called['__toString'];

        return 'foo';
    }

    public function foo()
    {
        ++self::$called['foo'];

        return 'foo';
    }

    public function getFooBar()
    {
        ++self::$called['getFooBar'];

        return 'foobar';
    }
}
