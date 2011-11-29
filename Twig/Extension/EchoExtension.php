<?php

namespace Admingenerator\GeneratorBundle\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;

class EchoExtension extends \Twig_Extension
{
    protected $loader;
    protected $controller;

    public function __construct(\Twig_LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'echo_twig'       => new \Twig_Function_Method($this, 'getEchoTwig'),
            'echo_block'      => new \Twig_Function_Method($this, 'getEchoBlock'),
            'echo_endblock'   => new \Twig_Function_Method($this, 'getEchoEndBlock'),
            'echo_for'        => new \Twig_Function_Method($this, 'getEchoFor'),
            'echo_endfor'     => new \Twig_Function_Method($this, 'getEchoEndFor'),
            'echo_extends'    => new \Twig_Function_Method($this, 'getEchoExtends'),
            'echo_if'         => new \Twig_Function_Method($this, 'getEchoIf'),
            'echo_if_granted' => new \Twig_Function_Method($this, 'getEchoIfGranted'),
            'echo_else'       => new \Twig_Function_Method($this, 'getEchoElse'),
            'echo_elseif'     => new \Twig_Function_Method($this, 'getEchoElseIf'),
            'echo_endif'      => new \Twig_Function_Method($this, 'getEchoEndIf'),
            'echo_path'       => new \Twig_Function_Method($this, 'getEchoPath'),
            'echo_set'        => new \Twig_Function_Method($this, 'getEchoSet'),
            'echo_trans'      => new \Twig_Function_Method($this, 'getEchoTrans'),
        );
    }

    public function getFilters()
    {
        return array(
            'as_php'          => new \Twig_Filter_Method($this, 'asPhp'),
            'convert_as_form' => new \Twig_Filter_Method($this, 'convertAsForm'),
        );
    }

    /**
     * Try to convert options of form given as string from yaml to a good object
     *
     * eg type option for collection type
     *
     * @param string $options the string as php
     * @param string $formType the form type
     *
     * @return string the new options
     */
    public function convertAsForm($options, $formType)
    {
        if ('collection' == $formType) {
            preg_match("/'type' => '(.+?)'/i", $options, $matches);

            if (count($matches) > 0) {
                $options = str_replace("'type' => '".$matches[1]."'", '\'type\' =>  new '.stripslashes($matches[1]).'()', $options);
            }
        }

        if ('model' == $formType) {
            preg_match("/'query' => '(.+?)',/i", $options, $matches);

            if (count($matches) > 0) {
                $options = str_replace("'query' => '".$matches[1]."'", '\'query\' => '.stripslashes($matches[1]), $options);
            }
        }

        if ('choice' == $formType) {
            preg_match("/'choices' => '(.+?)',/i", $options, $matches);

            if (count($matches) > 0) {
                $options = str_replace("'choices' => '".$matches[1]."'", '\'choices\' => '.stripslashes($matches[1]), $options);
            }
        }

        return $options;
    }

    public function asPhp($variable)
    {
       if (!is_array($variable)) {
           return $this->export($variable);
       }

       $str = $this->export($variable);

       preg_match_all('/[^> ]+::__set_state\(array\((.+),\'loaded/i', $str, $matches);

       if (isset($matches[1][0])) {
           $params = 'return array('.$matches[1][0].')';
           $params = eval($params. '?>');

           $str_param = '';
           foreach ($params as $p) {
               if ('' !== $str_param ) {
                   $str_param .= ', ';
               }
               $str_param .= $this->export($p);
           }

           $str = preg_replace("/([^> ]+)::__set_state\(/i", ' new \\\$0', $str);
           $str = str_replace('::__set_state', '', $str);
           $str = str_replace('array('.$matches[1][0].',\'loaded\' => false,  )', $str_param, $str);
       }

       return $str;

    }

    public function export($variable)
    {
        return str_replace(array("\n", 'array (', '     '), array('', 'array(', ''), var_export($variable, true));
    }

    public function getEchoTrans($str, $catalog = 'Admingenerator')
    {
        return '{% trans from "'.$catalog.'" %}'.$str.'{% endtrans %}';
    }

    public function getEchoSet($var, $value, $value_as_string = true)
    {
        if ($value_as_string) {
            return strtr('{% set %%var%% = "%%value%%" %}',array('%%var%%' => $var, '%%value%%' => $value));
        } else {
            return strtr('{% set %%var%% = %%value%% %}',array('%%var%%' => $var, '%%value%%' => $value));
        }
    }

    public function getEchopath($path, $params = null)
    {
        if (null === $params) {
            return strtr('{{ path("%%path%%") }}',array('%%path%%' => $path));
        }

        return strtr('{{ path("%%path%%", %%params%%) }}',array('%%path%%' => $path, '%%params%%'=>$params));
    }

    public function getEchoIfGranted($credentials)
    {
       return $this->getEchoIf('is_expr_granted(\''.$credentials.'\')');
    }

    public function getEchoIf($condition)
    {
        return str_replace('%%condition%%', $condition, '{% if %%condition%% %}');
    }

    public function getEchoElseIf($condition)
    {
        return str_replace('%%condition%%', $condition, '{% elseif %%condition%% %}');
    }

    public function getEchoElse()
    {
        return '{% else %}';
    }

    public function getEchoEndIf()
    {
        return '{% endif %}';
    }

    public function getEchoTwig($str)
    {
        return sprintf('{{ %s }}', $str);
    }

    public function getEchoBlock($name)
    {
        return str_replace('%%name%%', $name, '{% block %%name%% %}');
    }

    public function getEchoExtends($name)
    {
        return str_replace('%%name%%', $name, '{% extends "%%name%%" %}');
    }

    public function getEchoEndBlock()
    {
        return '{% endblock %}';
    }

    public function getEchoFor($object, $in)
    {
        return strtr('{% for %%object%% in %%in%% %}', array('%%object%%' => $object, '%%in%%' => $in ));
    }

    public function getEchoEndFor()
    {
        return '{% endfor %}';
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'echo';
    }
}
