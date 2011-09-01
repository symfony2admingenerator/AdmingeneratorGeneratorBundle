<?php

namespace Admingenerator\GeneratorBundle\Guesser;

use Symfony\Component\Locale\Exception\NotImplementedException;

use Symfony\Component\Form\Extension\Core\ChoiceList\ArrayChoiceList;

use Doctrine\ODM\MongoDB\DocumentManager;

class DoctrineODMFieldGuesser
{
    private $documentManager;
    
    private $metadata;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }
    
    protected function getMetadatas($class = null)
    {
        if(isset($this->metadata) || !$class) {
            return $this->metadata;
        }
        
        if (!$this->documentManager->getConfiguration()->getMetadataDriverImpl()->isTransient($class)) {
            $this->metadata = $this->documentManager->getClassMetadata($class);
        }
        
        return $this->metadata;
    }
    
    public function getDbType($class, $fieldName)
    {
        $metadata = $this->getMetadatas($class);
        
        if ($metadata->hasAssociation($fieldName)) {
            if ($metadata->isSingleValuedAssociation($fieldName)) {
                return 'document';
            } else {
                return 'collection';
            }
        }
        
        $mapping = $metadata->getFieldMapping($fieldName);
        
        return $mapping['type'];
        
        //return $metadata->getTypeOfField($fieldName);//Not Yet implemented by doctrine
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
            case 'id':
            case 'string':
                return 'text';
                break;
            case 'text':
                return 'textarea';
                break;
            case 'time':
                return 'time';
                break;
            case 'document':
                return 'document';
                break;
             case 'collection':
                return 'doctrine_odm_double_list';
                break;
            default:
                throw new NotImplementedException('The dbType "'.$dbType.'" is not yet implemented');
                break;
        }
    }
    
    public function getFilterType($dbType)
    {
         switch($dbType) {
             case 'text':
                return 'text';
                break;
             case 'boolean':
                return 'choice';
                break;
             case 'datetime':
             case 'vardatetime':
             case 'datetimetz':
             case 'date':
                return 'date_range';
                break;
             case 'collection':
                return 'document';
                break;        
         }
         
         return $this->getFormType($dbType);
    }
    
    public function getFormOptions($dbType, $columnName)
    {
        if ('boolean' == $dbType) {
            return array('required' => false);
        }
        
        if ('document' == $dbType) {
            $mapping = $this->getMetadatas()->getFieldMapping($columnName);
            
            return array( 'class' => $mapping['targetDocument'], 'multiple' => false);
        }
        
        if ('collection' == $dbType) {
            $mapping = $this->getMetadatas()->getFieldMapping($columnName);
            
            return array('class' => $mapping['targetDocument']);
        }
        
        return array('required' => $this->isRequired($columnName));
    }
    
    protected function isRequired($fieldName)
    {
        if(!$this->metadata->hasAssociation($fieldName) || $this->metadata->isSingleValuedAssociation($fieldName)) {
            return $this->metadata->isNullable($fieldName);
        }
        
        return false;
    }
    
    public function getFilterOptions($dbType, $ColumnName)
    {
        $options = array('required' => false);
        
        if('boolean' == $dbType)
        {
            $choices = new ArrayChoiceList(array(
                    0 => 'No',
                    1 => 'Yes'
                    ));
                    
           $options['choice_list'] = $choices;
           $options['empty_value'] = 'Yes or No';
        }
        
         if ('document' == $dbType) {
             return array_merge($this->getFormOptions($dbType, $ColumnName), $options);
         }
         
        if ('collection' == $dbType) {
             return array_merge($this->getFormOptions($dbType, $ColumnName), $options, array('multiple'=>false));
         }
        
        return $options;
    }

}
