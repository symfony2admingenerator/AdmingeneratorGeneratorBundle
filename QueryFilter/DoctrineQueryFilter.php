<?php

namespace Admingenerator\GeneratorBundle\QueryFilter;

use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\Query\Expr\Orx;

class DoctrineQueryFilter extends BaseQueryFilter
{
    /**
     * Default add filter behaviour.
     *
     * @param $name
     * @param $args
     */
    public function __call($name, $args = array())
    {
        if (preg_match('/add(.+)Filter/', $name)) {
            list($field, $value) = $args;
            $param = $this->getParamName();
            $this->query->andWhere($this->getComparison($field, $name, $param));
            $this->query->setParameter($param, $this->formatValue($value, $name, $field));
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
     * Get conjunction expression.
     *
     * @param array An array expressions.
     *
     * @return \Doctrine\ORM\Query\Expr\Andx
     */
    public function getConjunction($expressions)
    {
        return new Andx($expressions);
    }

    /**
     * Get disjunction expression.
     *
     * @param array An array expressions.
     *
     * @return \Doctrine\ORM\Query\Expr\Orx
     */
    public function getDisjunction($expressions)
    {
        return new Orx($expressions);
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
        $field = 'q.'.$field;
        $param = ':'.$param;

        switch ($operator) {
            case 'Equal':
                return $this->query->expr()->eq($field, $param);
            case 'NotEqual':
                return $this->query->expr()->neq($field, $param);
            case 'GreaterThan':
                return $this->query->expr()->gt($field, $param);
            case 'GreaterThanEqual':
                return $this->query->expr()->lt($field, $param);
            case 'LessThan':
                return $this->query->expr()->gte($field, $param);
            case 'LessThanEqual':
                return $this->query->expr()->lte($field, $param);
            case 'Like':
                return $this->query->expr()->like($field, $param);
            case 'NotLike':
                return $this->query->expr()->notLike($field, $param);
            case 'IsNull':
                return $this->query->expr()->isNull($field);
            case 'IsNotNull':
                return $this->query->expr()->isNotNull($field);
            // case 'Contains':
            //     return $this->query->expr()->in($param, $field);
            // case 'NotContains':
            //     return $this->query->expr()->notIn($param, $field);
            default:
                throw new \LogicException('Not implemented yet.');
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
}
