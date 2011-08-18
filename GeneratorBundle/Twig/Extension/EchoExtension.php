<?php

namespace Admingenerator\GeneratorBundle\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;

class EchoExtension extends \Twig_Extension
{
    protected $loader;
    protected $controller;

    public function __construct(FilesystemLoader $loader)
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
            'echo_else'       => new \Twig_Function_Method($this, 'getEchoElse'),
            'echo_elseif'     => new \Twig_Function_Method($this, 'getEchoElseIf'),
            'echo_endif'      => new \Twig_Function_Method($this, 'getEchoEndIf'),
            'echo_path'       => new \Twig_Function_Method($this, 'getEchoPath'),
            'echo_set'        => new \Twig_Function_Method($this, 'getEchoSet'),
        );
    }
    
    public function getFilters()
    {
        return array(
            'as_php'          => new \Twig_Filter_Method($this, 'asPhp'),
        );
    }

    public function asPhp($variable)
    {
       if(!is_array($variable)) {
           return $this->export($variable);
       }
       
       $str = $this->export($variable);
       
       preg_match_all('/[^> ]+::__set_state\(array\((.+),\'loaded/i', $str, $matches);
       
       if(isset($matches[1][0])) {
           $params = 'return array('.$matches[1][0].')';
           $params = eval($params. '?>');
           
           $str_param = '';
           foreach($params as $p) {
               if('' !== $str_param ) {
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
    
    public function getEchoSet($var, $value)
    {
        return strtr('{% set %%var%% = "%%value%%" %}',array('%%var%%' => $var, '%%value%%' => $value));
    }
    
    public function getEchopath($path, $params = null)
    {
        if(null === $params) {
            return strtr('{{ path("%%path%%") }}',array('%%path%%' => $path)); 
        }
        
        return strtr('{{ path("%%path%%", %%params%%) }}',array('%%path%%' => $path, '%%params%%'=>$params)); 
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