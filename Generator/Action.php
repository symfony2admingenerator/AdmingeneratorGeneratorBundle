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

    protected $crendentials;

    protected $condition = array();

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

    public function setCredentials($crendentials)
    {
        $this->crendentials = $crendentials;
    }

    public function getCredentials()
    {
        return $this->crendentials;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function setCondition(array $condition)
    {
        if (!is_array($condition)) {
            throw new \InvalidArgumentException(
                    sprintf(
                            'Invalid condition definition for "%s" action.',
                            $this->name
                    )
            );
        }

        if (!array_key_exists('function', $condition)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'You should define a "function" for "%s" action definition',
                    $this->name
                )
            );
        }

        $this->condition = array_merge(
            array(
                'parameters' => array(),
                'inverse' => false
            ),
            $condition
        );
    }

    public function isConditional()
    {
        return isset($this->condition['function']);
    }

    public function getConditionalService()
    {
        return array_key_exists('service', $this->condition) ? $this->condition['service'] : null;
    }

    public function getConditionalFunction()
    {
        // No BC Break
        return array_key_exists('function', $this->condition) ? $this->condition['function'] : null;
    }

    public function getConditionalParameters()
    {
        // No BC Break
        return array_key_exists('parameters', $this->condition) ? $this->condition['parameters'] : array();
    }

    public function getConditionalInverse()
    {
        // No BC Break
        return array_key_exists('inverse', $this->condition) ? $this->condition['inverse'] : false;
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
