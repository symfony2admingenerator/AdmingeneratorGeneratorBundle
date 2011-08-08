<?php

namespace Admingenerator\GeneratorBundle\Builder;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Inteface to define structure of the builders
 *
 * @author cedric Lombardot
 *
 */
interface BuilderInterface
{
    /**
     * Constructor
     */
    function __construct();

    /**
     * set generator element
     * @param Generator $generator
     */
    function setGenerator(Generator $generator);

    /**
     * Return generator element
     * @return Generator $generator
     */
    function getGenerator();

    /**
     * add $templateDir element
     * @param string $templateDir
     */
    function addTemplateDir($templateDir);

    /**
     * set a list of $templateDir elements
     * @param array $templateDirs
     */
    function setTemplateDirs($templateDirs);

    /**
     * @return array $templateDirs
     */
    function getTemplateDirs();

    /**
     * Find all default directories
     * @return array
     */
    function getDefaultTemplateDirs();

    /**
     * Set the $templateName
     * @param string $templateName
     */
    function setTemplateName($templateName);

    /**
     * @return string the template name
     */
    function getTemplateName();

    /**
     * @return string the default template name
     */
    function getDefaultTemplateName();

    /**
     * @param string the class name
     * @return string the class name without namespace
     */
    function getSimpleClassName($class = null);

    /**
     * The output filename
     * @param string $outputName
     */
    function setOutputName($outputName);

    /**
     * @return string the output name
     */
    function getOutputName();

    /**
     * @return boolean
     */
    function mustOverwriteIfExists();

    /**
     * @param array|ParameterBar $variables
     */
    function setVariables($variables);

    /**
     * @return array
     */
    function getVariables();

    /**
     * @return Boolean
     */
    function hasVariable($key);

    /**
     * @param string  $path    The key
     * @param mixed   $default The default value
     * @param boolean $deep
     *
     * @return mixed the variable of the parameter bag
     */
    function getVariable($path, $default = null, $deep = false);

    /**
     * Write the file on the disk
     * @param string $$outputDirectory the $outputDirectory
     */
    function writeOnDisk($outputDirectory);

    /**
     * @return string the parsed code to insert into the file
     */
    function getCode();

    /**
     * @param \Twig_Environment $twig
     */
    function addTwigFilters(\Twig_Environment $twig);
    
    /**
     * @return string the YamlKey
     */
    function getYamlKey();
}