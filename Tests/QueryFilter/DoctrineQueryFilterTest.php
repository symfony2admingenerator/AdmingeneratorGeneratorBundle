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

        if (!class_exists('\Doctrine\Tests\Mocks\DriverMock')) {
            $this->markTestSkipped('The "doctrine" service is not found.');
        }

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
        $em =  $this->_getTestEntityManager();
        $qb = new \Doctrine\ORM\QueryBuilder($em);

        $query = $qb->select('q')
                    ->from('Admingenerator\GeneratorBundle\Tests\QueryFilter\Entity\Movie', 'q');
        $queryFilter = new DoctrineQueryFilter();
        $queryFilter->setQuery($query);

        return $queryFilter;
    }


    /**
     * Creates an EntityManager for testing purposes.
     *
     * NOTE: The created EntityManager will have its dependant DBAL parts completely
     * mocked out using a DriverMock, ConnectionMock, etc. These mocks can then
     * be configured in the tests to simulate the DBAL behavior that is desired
     * for a particular test,
     *
     * @return Doctrine\ORM\EntityManager
     */
    protected function _getTestEntityManager($conn = null, $conf = null, $eventManager = null)
    {
        $metadataCache = new \Doctrine\Common\Cache\ArrayCache;

        $config = new \Doctrine\ORM\Configuration();

        $config->setMetadataCacheImpl($metadataCache);
        $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver());
        $config->setQueryCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
        $config->setProxyDir(__DIR__ . '/Proxies');
        $config->setProxyNamespace('Doctrine\Tests\Proxies');

        if ($conn === null) {
            $conn = array(
                'driverClass'  => '\Doctrine\Tests\Mocks\DriverMock',
                'wrapperClass' => '\Doctrine\Tests\Mocks\ConnectionMock',
                'user'         => 'john',
                'password'     => 'wayne'
            );
        }

        if (is_array($conn)) {
            $conn = \Doctrine\DBAL\DriverManager::getConnection($conn, $config, $eventManager);
        }

        return \Doctrine\Tests\Mocks\EntityManagerMock::create($conn, $config, $eventManager);
    }
}
