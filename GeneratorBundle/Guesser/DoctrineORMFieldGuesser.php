<?php

namespace Admingenerator\GeneratorBundle\Guesser;

use Doctrine\ORM\EntityManager;

class DoctrineORMFieldGuesser
{
    private $entityManager;
    
    private $metadata = array();

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    protected function getMetadatas($class)
    {
        if(isset($this->metadata[$class])) {
            return $this->metadata[$class];
        }
        
        if (!$this->entityManager->getConfiguration()->getMetadataDriverImpl()->isTransient($class)) {
            $this->metadata[$class] = $this->entityManager->getClassMetadata($class);
        }
        
        return $this->metadata[$class];
    }
    
    public function getDbType($class, $fieldName)
    {
        $metadata = $this->getMetadatas($class);
        return $metadata->getTypeOfField($fieldName);
    }
    
    public function getFormType($dbType)
    {
        switch($dbType) {
            case 'boolean':
                return 'checkbox';
            case 'datetime':
            case 'vardatetime':
            case 'datetimetz':
                return 'datetime';
            case 'date':
                return 'datetime';
                break;
            case 'decimal':
            case 'float':
                return 'number';
                break;
            case 'integer':
            case 'bigint':
            case 'smallint':
                return 'integer';
                break;
            case 'string':
                return 'text';
                break;
            case 'text':
                return 'textarea';
                break;
            case 'time':
                return 'time';
                break;
        }
    }
    
    public function getFilterType($dbType)
    {
         switch($dbType) {
             case 'text':
                return 'text';
                break;
         }
         
         return $this->getFormType($dbType);
    }
    
    public function getFormOptions($dbType)
    {
        if('boolean' == $dbType)
        {
            return array('required' => false);
        }
        
        return array();
    }
    
    public function getFilterOptions($dbType)
    {
        return array('required' => false);
    }

}
