<?php

namespace Admingenerator\GeneratorBundle\Controller\DoctrineODM;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * A base controller for DoctrineODM
 *
 * @author cedric Lombardot
 *
 */
abstract class BaseController extends Controller
{
    /**
     * @return \Doctrine\Bundle\MongoDBBundle\ManagerRegistry
     */
    protected function getDoctrineMongoDB()
    {
        if (!$this->container->has('doctrine_mongodb')) {
            throw new \LogicException('The DoctrineMongoDBBundle is not registered in your application.');
        }

        return $this->container->get('doctrine_mongodb');
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected function getDocumentManager()
    {
        return $this->get('doctrine.odm.mongodb.document_manager');
    }
}
