<?php

namespace Admingenerator\GeneratorBundle\Guesser;

use Admingenerator\GeneratorBundle\Exception\NotImplementedException;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\DependencyInjection\ContainerAware;

class DoctrineORMFieldGuesser extends ContainerAware
{
    private $doctrine;

    private $metadata;

    private static $current_class;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    protected function getMetadatas($class = null)
    {
        if ($class) {
            self::$current_class = $class;
        }

        if (isset($this->metadata[self::$current_class]) || !$class) {
            return $this->metadata[self::$current_class];
        }

        if (!$this->doctrine->getManagerForClass(self::$current_class)->getConfiguration()->getMetadataDriverImpl()->isTransient($class)) {
            $this->metadata[self::$current_class] = $this->doctrine->getManagerForClass(self::$current_class)->getClassMetadata($class);
        }

        return $this->metadata[self::$current_class];
    }

    public function getAllFields($class)
    {
        return $this->getMetadatas($class)->getFieldNames();
    }

    /**
     * Find out the database type for given model field path.
     * 
     * @param  string $model        The starting model.
     * @param  string $fieldPath    The field path.
     * @return string               The leaf field's primary key.
     */
    public function getDbType($model, $fieldPath)
    {
        $resolved = $this->resolveRelatedField($model, $fieldPath);
        $class = $resolved['class'];
        $field = $resolved['field'];

        if ($this->getMetadatas($class)->hasAssociation($field)) {
            if ($this->getMetadatas()->isSingleValuedAssociation($field)) {
                return 'entity';
            } else {
                return 'collection';
            }
        }

        if ($this->getMetadatas()->hasField($field)) {
            return $this->getMetadatas()->getTypeOfField($field);
        }

        return 'virtual';
    }

    public function getModelType($class, $fieldName)
    {
        $metadata = $this->getMetadatas($class);

        if ($metadata->hasAssociation($fieldName)) {
            return $metadata->getAssociationTargetClass($fieldName);
        }

        if ($metadata->hasField($fieldName)) {
            return $metadata->getTypeOfField($fieldName);
        }

        return 'virtual';
    }

    public function getSortType($dbType)
    {
        $alphabeticTypes = array(
            'string',
            'text',
        );

        $numericTypes = array(
            'decimal',
            'float',
            'integer',
            'bigint',
            'smallint',
        );

        if (in_array($dbType, $alphabeticTypes)) {
            return 'alphabetic';
        }

        if (in_array($dbType, $numericTypes)) {
            return 'numeric';
        }

        return 'default';
    }

    public function getFormType($dbType, $columnName)
    {
        $formTypes = $this->container->getParameter('admingenerator.doctrine_form_types');

        if (array_key_exists($dbType, $formTypes)) {
            return $formTypes[$dbType];
        } elseif ('virtual' === $dbType) {
            throw new NotImplementedException(
                'The dbType "'.$dbType.'" is only for list implemented '
                .'(column "'.$columnName.'" in "'.self::$current_class.'")'
            );
        } else {
            throw new NotImplementedException(
                'The dbType "'.$dbType.'" is not yet implemented '
                .'(column "'.$columnName.'" in "'.self::$current_class.'")'
            );
        }
    }

    public function getFilterType($dbType, $columnName)
    {
        $filterTypes = $this->container->getParameter('admingenerator.doctrine_filter_types');
        
        if (array_key_exists($dbType, $filterTypes)) {
            return $filterTypes[$dbType];
        } else {
           throw new NotImplementedException(
               'The dbType "'.$dbType.'" is not yet implemented '
               .'(column "'.$columnName.'" in "'.self::$current_class.'")'
           );
       }
    }

    public function getFormOptions($formType, $dbType, $columnName)
    {
        if ('boolean' == $dbType) {
            return array('required' => false);
        }

        if ('number' == $formType) {
            $mapping = $this->getMetadatas()->getFieldMapping($columnName);

            if (isset($mapping['scale'])) {
                $precision = $mapping['scale'];
            }

            if (isset($mapping['precision'])) {
                $precision = $mapping['precision'];
            }

            return array(
                'precision' => isset($precision) ? $precision : '',
                'required'  => $this->isRequired($columnName)
            );
        }

        if (preg_match("#^entity#i", $formType) || preg_match("#entity$#i", $formType)) {
            $mapping = $this->getMetadatas()->getAssociationMapping($columnName);

            return array(
                'multiple'  => ($mapping['type'] === ClassMetadataInfo::MANY_TO_MANY || $mapping['type'] === ClassMetadataInfo::ONE_TO_MANY),
                'em'        => 'default', // TODO: shouldn't this be configurable?
                'class'     => $mapping['targetEntity'],
                'required'  => $this->isRequired($columnName),
            );
        }

        if (preg_match("#^collection#i", $formType) || preg_match("#collection$#i", $formType)) {
            return array(
                'allow_add'     => true,
                'allow_delete'  => true,
                'by_reference'  => false,
            );
        }

        return array(
            'required' => $this->isRequired($columnName)
        );
    }

    protected function isRequired($fieldName)
    {
        $hasField = $this->getMetadatas()->hasField($fieldName);
        $hasAssociation = $this->getMetadatas()->hasAssociation($fieldName);
        $isSingleValAssoc = $this->getMetadatas()->isSingleValuedAssociation($fieldName);

        if ($hasField && (!$hasAssociation || $isSingleValAssoc)) {
            return !$this->getMetadatas()->isNullable($fieldName);
        }

        return false;
    }

    /**
     * Find the pk name for given class
     * 
     * @param  string $class The class name.
     * @return string Primary key field name.
     */
    public function getModelPrimaryKeyName($class = null)
    {
        return $this->getMetadatas($class)->getSingleIdentifierFieldName();
    }

    /**
     * Find out the primary key for given model field path.
     * 
     * @param  string $model        The starting model.
     * @param  string $fieldPath    The field path.
     * @return string               The leaf field's primary key.
     */
    public function getPrimaryKeyFor($model, $fieldPath)
    {
        $resolved = $this->resolveRelatedField($model, $fieldPath);
        $class = $resolved['class'];
        $field = $resolved['field'];

        if ($this->getMetadatas($class)->hasAssociation($field)) {
            $class = $this->getMetadatas()->getAssociationTargetClass($field);
            return $this->getModelPrimaryKeyName($class);
        } else {
            // if the leaf node is not an association
            return null;
        }
    }

    /**
     * Resolve field path for given model to class and field name.
     * 
     * @param  string $model        The starting model.
     * @param  string $fieldPath    The field path.
     * @return array                An array containing field and class information.
     */
    private function resolveRelatedField($model, $fieldPath)
    {
        $path = explode('.', $fieldPath);
        $field = array_pop($path);
        $class = $model;

        foreach ($path as $part) {
            if (!$this->getMetadatas($class)->hasAssociation($part)) {
                throw new \LogicException('Field "'.$part.'" for class "'.$class.'" is not an association.');
            }

            $class = $this->getMetadatas()->getAssociationTargetClass($part);
        }
        
        return array(
            'field' => $field,
            'class' => $class
        );
    }
}
