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
            'echo_twig' => new \Twig_Function_Method($this, 'getEchoTwig'),
            'echo_block' => new \Twig_Function_Method($this, 'getEchoBlock'),
            'echo_endblock' => new \Twig_Function_Method($this, 'getEchoEndBlock'),
            'echo_for' => new \Twig_Function_Method($this, 'getEchoFor'),
            'echo_endfor' => new \Twig_Function_Method($this, 'getEchoEndFor'),
            'echo_extends' => new \Twig_Function_Method($this, 'getEchoExtends'),
        );
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