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
	public function __construct();
	
	/**
	 * set generator element
	 * @param Generator $generator
	 */
	public function setGenerator(Generator $generator);
	
	/**
	 * Return generator element
	 * @return Generator $generator
	 */
	public function getGenerator();
	
	/**
	 * add $templateDir element
	 * @param string $templateDir
	 */
	public function addTemplateDir($templateDir);
	
	/**
	 * set a list of $templateDir elements
	 * @param array $templateDirs
	 */
	public function setTemplateDirs($templateDirs);
	
	/**
	 * @return array $templateDirs
	 */
	public function getTemplateDirs();
	
	/**
	 * Find all default directories
	 * @return array
	 */
	public function getDefaultTemplateDirs();
	
	/**
	 * Set the $templateName
	 * @param string $templateName
	 */
	public function setTemplateName($templateName);
	
	/**
	 * @return string the template name
	 */
	public function getTemplateName();
	
	/**
	 * @return string the default template name
	 */
	public function getDefaultTemplateName();
	
	/**
	 * @return string the class name without namespace
	 */
	public function getSimpleClassName();
	
	/**
	 * The output filename
	 * @param string $outputName
     */	
	public function setOutputName($outputName);
	
	/**
	 * @return string the output name
	 */
	public function getOutputName();
	
	/**
	 * @return boolean
	 */
	public function mustOverwriteIfExists();
	
	/**
	 * @param array|ParameterBar $variables
	 */
	public function setVariables($variables);
	
	/**
	 * @return array
	 */
	public function getVariables();
	
	/**
	 * @return Boolean
	 */
	public function hasVariable($key);
	
	/**
	 * @param string  $path    The key
     * @param mixed   $default The default value
     * @param boolean $deep
     * 
	 * @return mixed the variable of the parameter bag
	 */
	public function getVariable($path, $default = null, $deep = false);
	
	/**
	 * Write the file on the disk
	 * @param string $$outputDirectory the $outputDirectory
	 */
	public function writeOnDisk($outputDirectory);
	
	/**
	 * @return string the parsed code to insert into the file
	 */
	public function getCode();
	
	/**
	 * @param \Twig_Environment $twig
	 */
	public function addTwigFilters(\Twig_Environment $twig);
	
	/**
     * @return string the YamlKey
     */
    public function getYamlKey();
}