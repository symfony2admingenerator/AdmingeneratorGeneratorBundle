<?php

namespace Admingenerator\GeneratorBundle\Generator;

use Doctrine\Common\Util\Inflector;

/**
 *
 * This class describe an action
 * @author cedric Lombardot
 *
 */
class Action
{
    protected $name;

    protected $label;

    protected $route;

    protected $confirm_message;

    protected $crendentials;

    protected $params;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getLabel()
    {
        if ( isset ($this->label) ) {
            return $this->label;
        }
        return $this->humanize($this->getName());
    }

    public function getRoute()
    {
        return $this->route;
    }

    private function humanize($text)
    {
        return ucfirst(strtolower(str_replace('_', ' ', $text)));
    }

    public function setConfirm($confirm_message)
    {
        $this->confirm_message = $confirm_message;
    }

    public function getConfirm()
    {
        return $this->confirm_message;
    }

    public function setCredentials($crendentials)
    {
        $this->crendentials = $crendentials;
    }

    public function getCredentials()
    {
        return $this->crendentials;
    }

    public function setOption($option, $value)
    {
        $option = Inflector::classify($option);
        call_user_func_array(array($this, 'set'.$option), array($value));
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function setRoute($route)
    {
        $this->route = $route;
    }
}
