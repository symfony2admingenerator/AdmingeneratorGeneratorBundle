<?php

namespace Admingenerator\GeneratorBundle\QueryFilter;

class DoctrineODMQueryFilter extends BaseQueryFilter
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
            $param = $this->getUniqueName();
            $this->query->andWhere($this->getComparison($field, $matches['operator'], $param));
            $this->query->setParameter($param, $this->formatValue($value, $matches['operator'], $field));
        }
    }

    public function addIsNullFilter($field, $value)
    {
        $this->query->andWhere($this->getComparison($field, 'IsNull'));
    }

    public function addIsNotNullFilter($field, $value)
    {
        $this->query->andWhere($this->getComparison($field, 'IsNotNull'));
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
        $this->query->sort($field, $order);
    }

    /**
     * Get conjunction expression.
     *
     * @param array $expressions An array of expressions.
     *
     * @return \Doctrine\MongoDB\Query\Expr
     */
    public function getConjunction($expressions)
    {
        return $this->query->expr()->addAnd($expressions);
    }

    /**
     * Get disjunction expression.
     *
     * @param array $expressions An array of expressions.
     *
     * @return \Doctrine\MongoDB\Query\Expr
     */
    public function getDisjunction($expressions)
    {
        return $this->query->expr()->addOr($expressions);
    }

    /**
     * Get comparison expression.
     * 
     * @param string $field     The field name.
     * @param string $operator  The comparison operator.
     * @param string $param     The parameter name.
     * 
     * @return Doctrine\ORM\Query\Expr\Comparison
     */
    public function getComparison($field, $operator, $param = null)
    {
        $field = $this->addJoinFor($fieldPath);
        $param = ':'.$param;

        switch ($operator) {
            case 'Equal':
                return $this->query->expr()->field($field)->equals($param);
            case 'NotEqual':
                return $this->query->expr()->field($field)->notEqual($param);
            case 'GreaterThan':
                return $this->query->expr()->field($field)->gt($param);
            case 'GreaterThanEqual':
                return $this->query->expr()->field($field)->lt($param);
            case 'LessThan':
                return $this->query->expr()->field($field)->gte($param);
            case 'LessThanEqual':
                return $this->query->expr()->field($field)->lte($param);
            case 'Like':
                return $this->query->expr()->field($field)->equals(new \MongoRegex("/.*$param.*/i"));
            case 'NotLike':
                return $this->query->expr()->field($field)->notEqual(new \MongoRegex("/.*$param.*/i"));
            case 'IsNull':
                // TODO: not sure which one is correct
                return $this->query->expr()->field($field)->equals(null);
                //return $this->query->expr()->field($field)->type(10);
            case 'IsNotNull':
                // TODO: not sure which one is correct
                return $this->query->expr()->field($field)->notEqual(null);
                //return $this->query->expr()->not($this->query->expr()->field($field)->type(10));
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
                return new \MongoId($value->$getter());
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
        $field = array_pop($path);

        $alias = 'q';

        foreach ($path as $part) {
            $aliasName = $this->getUniqueAlias();
            $this->query->leftJoin($alias.'.'.$part, $aliasName);
            $alias = $aliasName;
        }

        $this->query->groupBy('q');

        return $alias.'.'.$field;
    }
}
