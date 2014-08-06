<?php

namespace Admingenerator\GeneratorBundle\QueryFilter;

use  Doctrine\Common\Util\Inflector;

class PropelQueryFilter extends BaseQueryFilter
{
    /**
     * Default add filter behaviour.
     *
     * @param $name
     * @param $args
     */
    public function __call($name, $args = array())
    {
        if (preg_match('/^add(?P<operator>.+)Filter$/', $name, $matches)) {
            list($field, $value) = $args;

            $formattedValue = $this->formatValue($value, $matches['operator'], $field);
            $conditionName = $this->getCondition($field, $matches['operator'], $formattedValue);

            $this->query->where(array($conditionName), 'and');
        }
    }

    public function addIsNullFilter($field, $value)
    {
        $conditionName = $this->getCondition($field, 'IsNull');
        $this->query->where(array($conditionName), 'and');
    }

    public function addIsNotNullFilter($field, $value)
    {
        $conditionName = $this->getCondition($field, 'IsNotNull');
        $this->query->where(array($conditionName), 'and');
    }

    /**
     * Sort query.
     * 
     * @param  string $fieldPath The sort field path.
     * @param  string $order     The sort order.
     */
    public function addSortBy($fieldPath, $order)
    {
        $field = $this->addJoinFor($fieldPath);
        $this->query->orderBy($field, $order);
    }

    /**
     * Get conjunction condition.
     *
     * @param array $conditions An array of condition names.
     *
     * @return string Condition name
     */
    public function getConjunction($conditions)
    {
        $conditionName = $this->getUniqueName();
        $this->query->combine($conditions, 'and', $conditionName);

        return $conditionName;
    }

    /**
     * Get disjunction condition.
     *
     * @param array $conditions An array of condition names.
     *
     * @return string Condition name
     */
    public function getDisjunction($conditions)
    {
        $conditionName = $this->getUniqueName();
        $this->query->combine($conditions, 'or', $conditionName);

        return $conditionName;
    }

    /**
     * Set condition and return it's name.
     * 
     * @param string $fieldPath The field path.
     * @param string $operator  The comparison operator.
     * @param string $value     The value.
     * 
     * @return string Condition name.
     */
    public function getCondition($field, $operator, $value = null)
    {
        $field = $this->addJoinFor($fieldPath);
        $conditionName = $this->getUniqueName();

        switch ($operator) {
            case 'Equal':
                $this->query->condition($conditionName, $field.' = ?', $value);
                return $conditionName;
            case 'NotEqual':
                $this->query->condition($conditionName, $field.' <> ?', $value);
                return $conditionName;
            case 'GreaterThan':
                $this->query->condition($conditionName, $field.' > ?', $value);
                return $conditionName;
            case 'GreaterThanEqual':
                $this->query->condition($conditionName, $field.' >= ?', $value);
                return $conditionName;
            case 'LessThan':
                $this->query->condition($conditionName, $field.' < ?', $value);
                return $conditionName;
            case 'LessThanEqual':
                $this->query->condition($conditionName, $field.' <= ?', $value);
                return $conditionName;
            case 'Like':
                $this->query->condition($conditionName, $field.' LIKE ?', $value);
                return $conditionName;
            case 'NotLike':
                $this->query->condition($conditionName, $field.' NOT LIKE ?', $value);
                return $conditionName;
            case 'IsNull':
                $this->query->condition($conditionName, $field.' IS NULL');
                return $conditionName;
            case 'IsNotNull':
                $this->query->condition($conditionName, $field.' IS NOT NULL');
                return $conditionName;
            // case 'Contains':
            // case 'NotContains':
            default:
                throw new \LogicException('Comparison for operator "'.$operator.'" is not implemented.');
        }
    }

    /**
     * (non-PHPdoc)
     * @see GeneratorBundle\QueryFilter.QueryFilterInterface::formatValue()
     */
    public function formatValue($value, $operator, $field)
    {
        switch ($this->getFilterFor($field)) {
            case 'boolean':
                return !!$value;
            case 'datetime':
                return $this->formatDate($value, 'Y-m-d H:i:s');
            case 'date':
                return $this->formatDate($value, 'Y-m-d');
            case 'model':
            case 'collection':
                $getter = 'get'.ucfirst($this->getPrimaryKeyFor($field));
                return $value->$getter();
        }

        switch ($operator) {
            case 'Like':
            case 'NotLike':
                return '%'.$value.'%';
        }

        return $value;
    }

    /**
     * Adds joins for path and returns the field alias and name.
     * 
     * @param  string $fieldPath The field path.
     * @return string            The field alias and name.
     */     
    public function addJoinFor($fieldPath)
    {
        $path = explode('.', $fieldPath);
        $field = Inflector::classify(array_pop($path));

        $alias = 'q';

        foreach ($path as $part) {
            $tableName = Inflector::classify($part);
            $aliasName = $this->getUniqueAlias();
            $this->query->leftJoin($alias.'.'.$tableName.' '.$aliasName);
            $alias = $aliasName;
        }

        return $alias.'.'.$field;
    }  
}
