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
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected function getDocumentManager()
    {
        return $this->get('doctrine.odm.mongodb.document_manager');
    }
}
