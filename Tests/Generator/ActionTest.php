<?php

namespace Admingenerator\GeneratorBundle\Tests\Generator;

use Admingenerator\GeneratorBundle\Tests\TestCase;

use Admingenerator\GeneratorBundle\Generator\Action;

class ActionTest extends TestCase
{

    public function testGetName()
    {
        $from_to_array = array(
            'name' => 'name',
            'underscored_name' => 'underscored_name',
        );

        $this->checkAction($from_to_array, 'getName');
    }

    public function testGetLabel()
    {
        $from_to_array = array(
            'name' => 'Name',
            'underscored_name' => 'Underscored name',
        );

        $this->checkAction($from_to_array, 'getLabel');
    }

    protected function checkAction($from_to_array, $method)
    {
        foreach ($from_to_array as $from => $to) {
            $action = new Action($from);
            $this->assertEquals($to, $action->$method());
        }
    }

}
