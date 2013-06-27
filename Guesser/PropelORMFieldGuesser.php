<?php

namespace Admingenerator\GeneratorBundle\Guesser;

use Admingenerator\GeneratorBundle\Exception\NotImplementedException;

use Doctrine\Common\Util\Inflector;
use Symfony\Component\DependencyInjection\ContainerAware;

class PropelORMFieldGuesser extends ContainerAware
{

    private $cache = array();

    private $metadata = array();

    private static $current_class;

    protected function getMetadatas($class = null)
    {
        if ($class) {
            self::$current_class = $class;
        }

        return $this->getTable(self::$current_class);
    }

    public function getAllFields($class)
    {
        $return = array();

        foreach ($this->getMetadatas($class)->getColumns() as $column) {
            $return[] = Inflector::tableize($column->getPhpName());
        }

        return $return;
    }

    public function getDbType($class, $fieldName)
    {
        if ( $relation = $this->getRelation($fieldName, $class)) {
            return \RelationMap::MANY_TO_ONE === $relation->getType() ? 'model' : 'collection';
        }

        return $this->getColumn($class, $fieldName)  ? $this->getColumn($class, $fieldName)->getType() : 'virtual';
    }

    protected function getRelation($fieldName, $class = null)
    {
        $table = $this->getMetadatas($class);
        $relName = Inflector::classify($fieldName);

        foreach ($table->getRelations() as $relation) {
            if ($relName === $relation->getName() || $relName === $relation->getPluralName()) {
                return $relation;
            }
        }

        return false;
    }

    public function getPhpName($class, $fieldName)
    {
        $column = $this->getColumn($class, $fieldName);

        if ($column) {
            return $column->getPhpName();
        }
    }

    public function getFormType($dbType, $columnName)
    {
        switch ($dbType) {
            case \PropelColumnTypes::ENUM:
                return 'choice';
            case \PropelColumnTypes::BOOLEAN:
            case \PropelColumnTypes::BOOLEAN_EMU:
                return 'checkbox';
            case \PropelColumnTypes::TIMESTAMP:
            case \PropelColumnTypes::BU_TIMESTAMP:
                return 'datetime';
            case \PropelColumnTypes::DATE:
            case \PropelColumnTypes::BU_DATE:
                return 'date';
            case \PropelColumnTypes::TIME:
                return 'time';
            case \PropelColumnTypes::FLOAT:
            case \PropelColumnTypes::REAL:
            case \PropelColumnTypes::DOUBLE:
            case \PropelColumnTypes::DECIMAL:
                return 'number';
            case \PropelColumnTypes::TINYINT:
            case \PropelColumnTypes::SMALLINT:
            case \PropelColumnTypes::INTEGER:
            case \PropelColumnTypes::BIGINT:
            case \PropelColumnTypes::NUMERIC:
                return 'integer';
            case \PropelColumnTypes::CHAR:
            case \PropelColumnTypes::VARCHAR:
                return 'text';
            case \PropelColumnTypes::LONGVARCHAR:
            case \PropelColumnTypes::BLOB:
            case \PropelColumnTypes::CLOB:
            case \PropelColumnTypes::CLOB_EMU:
                return 'textarea';
            case 'model':
                return 'model';
            case \PropelColumnTypes::PHP_ARRAY:
                return 'collection';
                break;
            case 'collection':
                return 'double_list';
            default:
                throw new NotImplementedException('The dbType "'.$dbType.'" is not yet implemented (column "'.$columnName.'" in "'.self::$current_class.'")');
        }
    }

    public function getFilterType($dbType, $columnName)
    {
         switch ($dbType) {
             case \PropelColumnTypes::BOOLEAN:
             case \PropelColumnTypes::BOOLEAN_EMU:
                return 'choice';
                break;
             case \PropelColumnTypes::TIMESTAMP:
             case \PropelColumnTypes::BU_TIMESTAMP:
             case \PropelColumnTypes::DATE:
             case \PropelColumnTypes::BU_DATE:
                return 'datepicker_range';
                break;
             case 'collection':
                return 'model';
                break;
         }

         return $this->getFormType($dbType, $columnName);
    }

    public function getFormOptions($formType, $dbType, $columnName)
    {
        if (\PropelColumnTypes::BOOLEAN == $dbType || \PropelColumnTypes::BOOLEAN_EMU == $dbType) {
            return array('required' => false);
        }

        if ('model' == $formType) {
            $relation = $this->getRelation($columnName);
            if ($relation) {
                if (\RelationMap::MANY_TO_ONE === $relation->getType()) {
                    return array('class' => $relation->getForeignTable()->getClassname(), 'multiple' => false);
                } else { // Many to many

                    return array('class' => $relation->getLocalTable()->getClassname(), 'multiple' => false);
                }
            }
        }

        if ('propel_double_list' == $formType) {
            $relation = $this->getRelation($columnName);
            if ($relation) {
                if (\RelationMap::MANY_TO_ONE === $relation->getType()) {
                    return array('class' => $relation->getForeignTable()->getClassname());
                } else { // Many to many

                    return array('class' => $relation->getLocalTable()->getClassname());
                }
            }
        }

        if ('collection' == $formType) {
            return array('allow_add' => true, 'allow_delete' => true, 'by_reference' => false);
        }

        if (\PropelColumnTypes::ENUM == $dbType) {
            $valueSet = $this->getMetadatas()
                                   ->getColumn($columnName)
                                   ->getValueSet();

            return array(
                'required' => $this->isRequired($columnName),
                'choices'  => array_combine($valueSet, $valueSet),
            );
        }

        return array('required' => $this->isRequired($columnName));
    }

    protected function isRequired($fieldName)
    {
        if ($column = $this->getColumn(self::$current_class, $fieldName)) {
            return $column->isNotNull();
        }

        return false;
    }

    public function getFilterOptions($formType, $dbType, $ColumnName)
    {
        $options = array('required' => false);

        if (\PropelColumnTypes::BOOLEAN == $dbType || \PropelColumnTypes::BOOLEAN_EMU == $dbType) {
            $options['choices'] = array(
               0 => $this->container->get('translator')->trans('boolean.no', array(), 'Admingenerator'),
               1 => $this->container->get('translator')->trans('boolean.yes', array(), 'Admingenerator')
            );
            $options['empty_value'] = $this->container->get('translator')->trans('boolean.yes_or_no', array(), 'Admingenerator');
        }

        if (\PropelColumnTypes::ENUM == $dbType) {
            $valueSet = $this->getMetadatas()
                                   ->getColumn($ColumnName)
                                   ->getValueSet();

            return array(
                'required' => false,
                'choices'  => array_combine($valueSet, $valueSet),
            );
        }

         if ('model' == $dbType) {
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
        $pks = $this->getMetadatas($class)->getPrimaryKeyColumns();

        if (count($pks) == 1) {
            return $pks[0]->getPhpName();
        }

        throw new \LogicException('No valid primary keys found');
    }

    protected function getTable($class)
    {
        if (isset($this->cache[$class])) {
            return $this->cache[$class];
        }

        if (class_exists($queryClass = $class.'Query')) {
            $query = new $queryClass();

            return $this->cache[$class] = $query->getTableMap();
        }

        throw new \LogicException('Can\'t find query class '.$queryClass);
    }

    protected function getColumn($class, $property)
    {
        if (isset($this->cache[$class.'::'.$property])) {
            return $this->cache[$class.'::'.$property];
        }

        $table = $this->getTable($class);

        if ($table && $table->hasColumn($property)) {
            return $this->cache[$class.'::'.$property] = $table->getColumn($property);
        } else {
            foreach ($table->getColumns() as $column) {
                if (Inflector::tableize($column->getPhpName()) === $property || $column->getPhpName() === ucfirst($property)) {
                    return $this->cache[$class.'::'.$property] = $column;
                }
            }
        }
    }

}
