<?php 


namespace Admingenerator\GeneratorBundle\Tests\Generator;

use Admingenerator\GeneratorBundle\Tests\TestCase;

use Admingenerator\GeneratorBundle\Generator\Column;

class ColumnTest extends TestCase
{
    
    public function testGetName()
    {
        $from_to_array = array(
            'name' => 'name',
            'underscored_name' => 'underscored_name',
        );
        
        $this->checkColumn($from_to_array, 'getName');
    }
    
    public function testGetGetter()
    {
        $from_to_array = array(
            'name' => 'name',
            'underscored_name' => 'underscoredName',
        );
        
        $this->checkColumn($from_to_array, 'getGetter');
    }
    
    public function testGetLabel()
    {
        $from_to_array = array(
            'name' => 'Name',
            'underscored_name' => 'Underscored name',
        );
        
        $this->checkColumn($from_to_array, 'getLabel');
    }
    
    protected function checkColumn($from_to_array, $method)
    {
        foreach ($from_to_array as $from => $to) {
            $column = new Column($from);
            $this->assertEquals($to, $column->$method());
        }
    }
     
}