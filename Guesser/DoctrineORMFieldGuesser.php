<?php

namespace Admingenerator\GeneratorBundle\Guesser;

use Symfony\Component\Locale\Exception\NotImplementedException;

use Symfony\Component\Form\Extension\Core\ChoiceList\ArrayChoiceList;

use Doctrine\ORM\EntityManager;

class DoctrineORMFieldGuesser
{
    private $entityManager;

    private $metadata;

    private static $current_class;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function getMetadatas($class = null)
    {
        if ($class) {
            self::$current_class = $class;
        }

        if (isset($this->metadata[self::$current_class]) || !$class) {
            return $this->metadata[self::$current_class];
        }

        if (!$this->entityManager->getConfiguration()->getMetadataDriverImpl()->isTransient($class)) {
            $this->metadata[self::$current_class] = $this->entityManager->getClassMetadata($class);
        }

        return $this->metadata[self::$current_class];
    }

    public function getDbType($class, $fieldName)
    {
        $metadata = $this->getMetadatas($class);

        if ($metadata->hasAssociation($fieldName)) {
            if ($metadata->isSingleValuedAssociation($fieldName)) {
                return 'entity';
            } else {
                return 'collection';
            }
        }

        if ($metadata->hasField($fieldName)) {
            return $metadata->getTypeOfField($fieldName);
        }

        return 'virtual';
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
            case 'entity':
                return 'entity';
                break;
             case 'collection':
                return 'doctrine_double_list';
                break;
            case 'virtual':
                return '';
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
                return 'entity';
                break;
         }

         return $this->getFormType($dbType);
    }

    public function getFormOptions($formType, $dbType, $columnName)
    {
        if ('boolean' == $dbType) {
            return array('required' => false);
        }

        if ('entity' == $formType) {

            $mapping = $this->getMetadatas()->getAssociationMapping($columnName);

            return array('em' => 'default', 'class' => $mapping['targetEntity'], 'multiple' => false);
        }

        if ('doctrine_double_list' == $formType) {
            $mapping = $this->getMetadatas()->getAssociationMapping($columnName);

            return array('em' => 'default', 'class' => $mapping['targetEntity']);
        }

        return array('required' => $this->isRequired($columnName));
    }

    protected function isRequired($fieldName)
    {
        if ($this->getMetadatas()->hasField($fieldName) && 
            (!$this->getMetadatas()->hasAssociation($fieldName) || $this->getMetadatas()->isSingleValuedAssociation($fieldName))) {
            return $this->getMetadatas()->isNullable($fieldName);
        }

        return false;
    }

    public function getFilterOptions($formType, $dbType, $ColumnName)
    {
        $options = array('required' => false);

        if ('boolean' == $dbType)
        {
            $choices = new ArrayChoiceList(array(
                    0 => 'No',
                    1 => 'Yes'
                    ));

           $options['choice_list'] = $choices;
           $options['empty_value'] = 'Yes or No';
        }

         if ('entity' == $dbType) {
             return array_merge($this->getFormOptions($formType, $dbType, $ColumnName), $options);
         }

        if ('collection' == $dbType) {
             return array_merge($this->getFormOptions($formType, $dbType, $ColumnName), $options, array('multiple'=>false));
         }

        return $options;
    }

}
