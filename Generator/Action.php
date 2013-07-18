<?php

namespace Admingenerator\GeneratorBundle\Generator;

use Doctrine\Common\Util\Inflector;

/**
 * This class describes an action
 *
 * @author cedric Lombardot
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class Action
{
    protected $name;

    protected $type;

    protected $label;

    protected $icon;

    protected $class;

    protected $options = array();

    protected $submit;

    protected $route;

    protected $params = array();

    protected $confirm_message;

    protected $csrf_protected = false;

    protected $credentials;

    /**
     * To be removed
     *
     * @deprecated use credentials instead and SecurityFunction annotation
     * from JMS\DiExtraBundle
     */
    protected $conditional_function;

    /**
     * To be removed
     *
     * @deprecated use credentials instead and SecurityFunction annotation
     * from JMS\DiExtraBundle
     */
    protected $conditional_parameters = array();

    /**
     * To be removed
     *
     * @deprecated use credentials instead and SecurityFunction annotation
     * from JMS\DiExtraBundle
     */
    protected $conditional_inverse = false;

    public function __construct($name, $type = 'custom')
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function setProperty($option, $value)
    {
        $option = Inflector::classify($option);
        call_user_func_array(array($this, 'set'.$option), array($value));
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTwigName()
    {
        return str_replace('-', '_', $this->name);
    }

    public function getType()
    {
        return $this->type;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getLabel()
    {
        if (isset($this->label)) {
            return $this->label;
        }

        return $this->humanize($this->getName());
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setClass($class)
    {
        $this->class = $class;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function setRoute($route)
    {
        $this->route = $route;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setSubmit($submit)
    {
        $this->submit = (bool) $submit;
    }

    public function getSubmit()
    {
        return $this->submit;
    }

    private function humanize($text)
    {
        return ucfirst(str_replace('_', ' ', $text));
    }

    public function setConfirm($confirm_message)
    {
        $this->confirm_message = $confirm_message;
    }

    public function getConfirm()
    {
        return $this->confirm_message;
    }

    public function setCsrfProtected($csrf_protected)
    {
        $this->csrf_protected = $csrf_protected;
    }

    public function getCsrfProtected()
    {
        return $this->csrf_protected;
    }

    public function setCredentials($credentials)
    {
        $this->credentials = $credentials;
    }

    public function getCredentials()
    {
        return $this->credentials;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * To be removed
     *
     * @deprecated use credentials instead and SecurityFunction annotation
     * from JMS\DiExtraBundle
     */
    public function setCondition(array $condition)
    {
        if (!isset($condition['function'])) {
            return false;
        }

        $this->conditional_function = $condition['function'];

        if (isset($condition['parameters'])) {
            $this->conditional_parameters = (array) $condition['parameters'];
        }

        if (isset($condition['inverse'])) {
            $this->conditional_inverse = (boolean) $condition['inverse'];
        }
    }

    /**
     * To be removed
     *
     * @deprecated use credentials instead and SecurityFunction annotation
     * from JMS\DiExtraBundle
     */
    public function getConditionalFunction()
    {
        return $this->conditional_function;
    }

    /**
     * To be removed
     *
     * @deprecated use credentials instead and SecurityFunction annotation
     * from JMS\DiExtraBundle
     */
    public function getConditionalParameters()
    {
        return $this->conditional_parameters;
    }

    /**
     * To be removed
     *
     * @deprecated use credentials instead and SecurityFunction annotation
     * from JMS\DiExtraBundle
     */
    public function getConditionalInverse()
    {
        return $this->conditional_inverse;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
    }
}
