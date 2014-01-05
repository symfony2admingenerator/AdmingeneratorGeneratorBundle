<?php

namespace Admingenerator\GeneratorBundle\QueryFilter;

abstract class BaseQueryFilter implements QueryFilterInterface
{
    protected $query;

    /**
     * (non-PHPdoc)
     * @see GeneratorBundle\QueryFilter.QueryFilterInterface::setQuery()
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * (non-PHPdoc)
     * @see GeneratorBundle\QueryFilter.QueryFilterInterface::getQuery()
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * (non-PHPdoc)
     * @see \GeneratorBundle\QueryFilter.QueryFilterInterface::addDefaultFilter()
     */
    public function addDefaultFilter($field, $value)
    {
        throw new \LogicException('No method defined to execute this type of filters');
    }

    /**
     *
     * By default we call addDefaultFilter
     *
     * @param $name
     * @param $values
     */
    public function __call($name, $values = array())
    {
        if (preg_match('/add(.+)Filter/', $name)) {
            $this->addDefaultFilter($values[0], $values[1]);
        }
    }

    protected function formatDate($date, $format)
    {
        if (!($date instanceof \DateTime)) {
            $date = new \DateTime($date);
        }

        if (false !== $date) {
            return $date->format($format);
        }

        return $date;
    }
}
