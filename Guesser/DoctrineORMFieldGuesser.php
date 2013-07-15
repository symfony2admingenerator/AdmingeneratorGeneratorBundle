<?php

namespace Admingenerator\GeneratorBundle\Guesser;

use Admingenerator\GeneratorBundle\Exception\NotImplementedException;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\DependencyInjection\ContainerAware;

class DoctrineORMFieldGuesser extends ContainerAware
{
    private $doctrine;

    private $entityManager;

    private $metadata;

    private static $current_class;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function setEntityManager($manager = null)
    {
        $this->entityManager = $this->doctrine->getManager($manager);
    }

    public function getEntityManager()
    {
        return $this->entityManager;
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

    public function getAllFields($class)
    {
        return $this->getMetadatas($class)->getFieldNames();
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

    public function getFormType($dbType, $columnName)
    {
        $formTypes = $this->container->getParameter('admingenerator.doctrine_form_types');  
        
        if (in_array($dbType, $formTypes)) {
            return $formTypes[$dbType];
        } elseif ('virtual' === $dbType) {
            throw new NotImplementedException('The dbType "'.$dbType.'" is only for list implemented (column "'.$columnName.'" in "'.self::$current_class.'")');
        } else {
            throw new NotImplementedException('The dbType "'.$dbType.'" is not yet implemented (column "'.$columnName.'" in "'.self::$current_class.'")');
        }
    }

    public function getFilterType($dbType, $columnName)
    {
        $filterTypes = $this->container->getParameter('admingenerator.doctrine_filter_types');  
        
        if (in_array($dbType, $filterTypes)) {
            return $filterTypes[$dbType];
        }

        return $this->getFormType($dbType, $columnName);
    }

    public function getFormOptions($formType, $dbType, $columnName)
    {
        if ('boolean' == $dbType) {
            return array('required' => false);
        }

        if ('number' == $formType) {
            $mapping = $this->getMetadatas()->getFieldMapping($columnName);

            if (isset($mapping['scale']))
              $precision = $mapping['scale'];
            if (isset($mapping['precision']))
              $precision = $mapping['precision'];

            return array(
            'precision' => isset($precision) ? $precision : '',
                'required'  => $this->isRequired($columnName)
            );
        }

        if ('entity' == $formType) {

            $mapping = $this->getMetadatas()->getAssociationMapping($columnName);

            return array('em' => 'default', 'class' => $mapping['targetEntity'], 'multiple' => false, 'required' => $this->isRequired($columnName));
        }

        if ('collection' == $formType) {
            return array('allow_add' => true, 'allow_delete' => true, 'by_reference' => true);
        }

        return array('required' => $this->isRequired($columnName));
    }

    protected function isRequired($fieldName)
    {
        if ($this->getMetadatas()->hasField($fieldName) &&
            (!$this->getMetadatas()->hasAssociation($fieldName) || $this->getMetadatas()->isSingleValuedAssociation($fieldName))) {
            return !$this->getMetadatas()->isNullable($fieldName);
        }

        return false;
    }

    public function getFilterOptions($formType, $dbType, $ColumnName)
    {
        $options = array('required' => false);

        if ('boolean' == $dbType) {
            $options['choices'] = array(
               0 => $this->container->get('translator')->trans('boolean.no', array(), 'Admingenerator'),
               1 => $this->container->get('translator')->trans('boolean.yes', array(), 'Admingenerator')
            );
            $options['empty_value'] = $this->container->get('translator')->trans('boolean.yes_or_no', array(), 'Admingenerator');
        }

        if ('entity' == $dbType) {
           return array_merge($this->getFormOptions($formType, $dbType, $ColumnName), $options);
        }

        if ('collection' == $dbType) {
           return array_merge($this->getFormOptions($formType, $dbType, $ColumnName), $options, array('multiple'=>false));
        }

        return $options;
    }

    /**
     * Find the pk name
     */
    public function getModelPrimaryKeyName($class = null)
    {
        return $this->getMetadatas($class)->getSingleIdentifierFieldName();
    }
}
