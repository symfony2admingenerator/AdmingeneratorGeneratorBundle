<?php 

namespace Admingenerator\GeneratorBundle\Tests\QueryFilter;

use Admingenerator\GeneratorBundle\Tests\TestCase;

use Admingenerator\GeneratorBundle\QueryFilter\DoctrineQueryFilter;

class QueryFilterTest extends TestCase
{
    protected $_container;
    
    public function setUp()
    {
        parent::setUp();
        $kernel = new \AppKernel("test", true);
        $kernel->boot();
        $this->_container = $kernel->getContainer();
    }
    
    protected function getContainer()
    {
        return $this->_container;
    }
    
    public function testAddStringFilter()
    {
        $queryFilter = $this->initQueryFilter();
        $queryFilter->addStringFilter('title', 'test');
        
        $this->assertEquals('SELECT q FROM Admingenerator\GeneratorBundle\Tests\QueryFilter\Entity\Movie q WHERE q.title LIKE :title', $queryFilter->getQuery()->getDql());       
    }
    
    public function testAddDefaultFilter()
    {
        $queryFilter = $this->initQueryFilter();
        $queryFilter->addDefaultFilter('title', 'test');
        
        $this->assertEquals('SELECT q FROM Admingenerator\GeneratorBundle\Tests\QueryFilter\Entity\Movie q WHERE q.title = :title', $queryFilter->getQuery()->getDql());       
    }
    
    public function testCall()
    {
        $queryFilter = $this->initQueryFilter();
        $queryFilter->addFooFilter('title', 'test');
        
        $this->assertEquals('SELECT q FROM Admingenerator\GeneratorBundle\Tests\QueryFilter\Entity\Movie q WHERE q.title = :title', $queryFilter->getQuery()->getDql()); 
    }
    
    public function initQueryFilter()
    {
        $query = $this->getContainer()
                    ->get('doctrine')
                    ->getEntityManager()
                    ->createQueryBuilder()
                    ->select('q')
                    ->from('Admingenerator\GeneratorBundle\Tests\QueryFilter\Entity\Movie', 'q');
        $queryFilter = new DoctrineQueryFilter();
        $queryFilter->setQuery($query);
        return $queryFilter;
    }
}