<?php

namespace Admingenerator\GeneratorBundle\Generator;

/**
 *
 * This class describe a column
 * @author cedric Lombardot
 *
 */
use Doctrine\Common\Util\Inflector;

class Column
{
    protected $name;

    protected $sortable;

    protected $sortOn;

    protected $filterOn;

    protected $dbType;

    protected $formType;

    protected $formOptions = array();

    protected $getter;

    protected $label;

    protected $help;

    protected $crendentials;

    /** For special columns template */
    protected $extras;

    public function __construct($name)
    {
        $this->name     = $name;
        $this->sortable = true;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getGetter()
    {
        return $this->getter ? $this->getter : Inflector::camelize($this->name);
    }

    public function setGetter($getter)
    {
        $this->getter = $getter;
    }

    public function getLabel()
    {
        return $this->label ? $this->label : $this->humanize($this->getName());
    }

    public function setLabel($label)
    {
        return $this->label = $label;
    }

    public function getHelp()
    {
        return $this->help;
    }

    public function setHelp($help)
    {
        return $this->help = $help;
    }

    public function isSortable()
    {
        return $this->isReal() && $this->sortable;
    }

    public function isReal()
    {
        return $this->dbType != 'virtual';
    }

    public function getSortable()
    {
        return $this->sortable;
    }

    public function setSortable($sortable)
    {
        return $this->sortable = ($sortable === 'true');
    }

    public function getSortOn()
    {
        return $this->sortOn != "" ? $this->sortOn : $this->name;
    }

    public function setSortOn($sort_on)
    {
        return $this->sortOn = $sort_on;
    }

    public function getFilterOn()
    {
        return $this->filterOn != "" ? $this->filterOn : $this->name;
    }

    public function setFilterOn($filter_on)
    {
        return $this->filterOn = $filter_on;
    }

    private function humanize($text)
    {
        return ucfirst(strtolower(str_replace('_', ' ', $text)));
    }

    public function setDbType($dbType)
    {
        $this->dbType = $dbType;
    }

    public function getDbType()
    {
        return $this->dbType;
    }

    public function setFormType($formType)
    {
        $this->formType = $formType;
    }

    public function getFormType()
    {
        return $this->formType;
    }

    public function setFormOptions($formOptions)
    {
        $this->formOptions = $formOptions;
    }

    public function getFormOptions()
    {
        return $this->formOptions;
    }

    public function setOption($option, $value)
    {
        $option = Inflector::classify($option);
        call_user_func_array(array($this, 'set'.$option), array($value));
    }

    public function setCredentials($crendentials)
    {
        $this->crendentials = $crendentials;
    }

    public function getCredentials()
    {
        return $this->crendentials;
    }

    public function setAddFormOptions(array $complementary_options = array())
    {
        foreach ($complementary_options as $option => $value) {
            if (is_array($value)) {
                foreach ($value as $k=>$v) {
                    if (preg_match('/\.(.+)/i', $k, $matches)) { //enable to call php function to build your form options
                        $value = call_user_func_array($matches[1], $v);
                    }
                }
            }

            $this->formOptions[$option] = $value;
        }
    }

    public function setExtras(array $values)
    {
        $this->extras = $values;
    }

    public function getExtras()
    {
        return $this->extras;
    }
}
