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
        );
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