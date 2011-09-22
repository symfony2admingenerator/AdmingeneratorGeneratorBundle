<?php

namespace Admingenerator\GeneratorBundle\Tests\QueryFilter;

use Admingenerator\GeneratorBundle\Tests\TestCase;

use Admingenerator\GeneratorBundle\QueryFilter\DoctrineQueryFilter;

class QueryFilterTest extends TestCase
{
    protected $queryFilter;

    public function setUp()
    {
        parent::setUp();
        $this->queryFilter = $this->initQueryFilter();
    }

    public function testAddStringFilter()
    {
        $this->queryFilter->addStringFilter('title', 'test');

        $this->assertEquals('SELECT q FROM Admingenerator\GeneratorBundle\Tests\QueryFilter\Entity\Movie q WHERE q.title LIKE :title', $this->queryFilter->getQuery()->getDql());
    }

    public function testAddDefaultFilter()
    {
        $this->queryFilter->addDefaultFilter('title', 'test');

        $this->assertEquals('SELECT q FROM Admingenerator\GeneratorBundle\Tests\QueryFilter\Entity\Movie q WHERE q.title = :title', $this->queryFilter->getQuery()->getDql());
    }

    public function testCall()
    {
        $this->queryFilter->addFooFilter('title', 'test');

        $this->assertEquals('SELECT q FROM Admingenerator\GeneratorBundle\Tests\QueryFilter\Entity\Movie q WHERE q.title = :title', $this->queryFilter->getQuery()->getDql());
    }

    protected function initQueryFilter()
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
