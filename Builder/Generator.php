<?php

namespace Admingenerator\GeneratorBundle\Builder;

/**
 * @author Cedric LOMBARDOT
 */
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\Yaml\Yaml;
use TwigGenerator\Builder\Generator as TwigGeneratorGenerator;
use TwigGenerator\Builder\BuilderInterface;

class Generator extends TwigGeneratorGenerator
{
    const TEMP_DIR_PREFIX = 'Admingenerator';

    /**
     * @var file $yaml the yaml
     */
    protected $yaml;

    protected $baseController;

    protected $columnClass = 'Admingenerator\GeneratorBundle\Generator\Column';

    protected $base_admin_template = 'AdmingeneratoroldThemeBundle::base_admin.html.twig';

    protected $base_generator_name;

    protected $container;

    /**
     * Init a new generator and automatically define the base of tempDir
     *
     * @param Filepath $cacheDir
     * @param Filepath $yaml
     */
    public function __construct($cacheDir, $yaml)
    {
        parent::__construct($cacheDir);
        $this->setYamlConfig(Yaml::parse($yaml));
    }

    public function getBaseAdminTemplate()
    {
        return $this->base_admin_template;
    }

    public function setBaseAdminTemplate($base_admin_template)
    {
        return $this->base_admin_template = $base_admin_template;
    }

    /**
     * Add a builder
     * @param BuilderInterface $builder
     */
    public function addBuilder(BuilderInterface $builder)
    {
        parent::addBuilder($builder);
        
        $builder->setVariables(
            $this->recursiveReplace(
                $this->getFromYaml('params', array()),
                $this->getFromYaml(sprintf('builders.%s.params', $builder->getYamlKey()), array())
            )
        );
        $builder->setColumnClass($this->getColumnClass());
    }
    
    /**
     * Recursively replaces Base array values with Replacedement array values
     * while keeping indexes of Replacement array
     * 
     * @param array $base Base array
     * @param array $replacement Replacement array
     */
    protected function recursiveReplace($base, $replacement)
    {
        $replace_values_recursive = function (array $array, array $order) use (&$replace_values_recursive) {
            $array = array_replace($order, array_replace($array, $order));

            foreach ($array as $key => &$value) {
                if (is_array($value))  {
                    $value = (array_key_exists($key, $order) && is_array($order[$key])) ? $replace_values_recursive($value, $order[$key]) : $value;
                }
            }
            return $array;
        };
        
        return $replace_values_recursive($base, $replacement);
    }


    protected function getColumnClass()
    {
        return $this->columnClass;
    }

    public function setColumnClass($columnClass)
    {
        return $this->columnClass = $columnClass;
    }

    /**
     * Set the yaml to pass all the vars to the builders
     * 
     * @param Yaml $yaml
     */
    protected function setYamlConfig(array $yaml)
    {
        $this->yaml = array_replace_recursive(
            Yaml::parse(__DIR__.'/../Resources/config/default.yml'), 
            $yaml
        );
    }

    /**
     * @param $yaml_path string with point for levels
     */
    public function getFromYaml($yaml_path, $default = null)
    {
        $search_in = $this->yaml;
        $yaml_path = explode('.',$yaml_path);
        foreach ($yaml_path as $key) {
            if (!isset($search_in[$key])) {
                return $default;
            }
            $search_in = $search_in[$key];
        }

        return $search_in;
    }

    public function setFieldGuesser($fieldGuesser)
    {
        return $this->fieldGuesser = $fieldGuesser;
    }

    public function getFieldGuesser()
    {
        return $this->fieldGuesser;
    }

    public function setBaseController($baseController)
    {
        $this->baseController = $baseController;
    }

    public function getBaseController()
    {
        return $this->baseController;
    }

    public function setBaseGeneratorName($base_generator_name)
    {
        $this->base_generator_name = $base_generator_name;
    }

    public function getBaseGeneratorName()
    {
        return $this->base_generator_name;
    }

    public function getGeneratedControllerFolder()
    {
        return 'Base'.$this->base_generator_name.'Controller';
    }

    public function setContainer(ContainerInterface $container)
    {
        return $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getTwigParams()
    {
        return $this->getContainer()->getParameter('admingenerator.twig');
    }
}
