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
            $this->mergeParameters(
                $this->getFromYaml('params', array()),
                $this->getFromYaml(sprintf('builders.%s.params', $builder->getYamlKey()), array())
            )
        );
        $builder->setColumnClass($this->getColumnClass());
    }

    /**
     * Merge parameters from global definition with builder definition
     * Fields and actions have special behaviors:
     *     - fields are merged and all global fields are still available
     *     from a builder
     *     - actions depend of builder. List of available actions come
     *     from builder, configuration is a merge between builder configuration
     *     and global configuration
     *
     * @param array $global
     * @param array $builder
     * @return array
     */
    protected function mergeParameters(array $global, array $builder)
    {
        foreach ($global as $param => &$value) {
            if (array_key_exists($param, $builder)) {
                if (in_array($param, array('fields', 'object_actions', 'batch_actions'))) {
                    $configurations = array();
                    foreach ($builder[$param] as $name => $configuration) {
                        if (is_array($configuration) || is_null($configuration)) {
                            if (!is_null($value) && array_key_exists($name, $value)) {
                                $configurations[$name] = $configuration
                                    ? $this->mergeConfiguration($value[$name], $configuration) // Override definition
                                    : $value[$name] // Configuration is null => use global definition
                                ;
                            } else {
                                // New definition (new field, new action) from builder
                                $configurations[$name] = $configuration;
                            }
                        } else {
                            throw new \InvalidArgumentException(
                                sprintf('Invalid %s "%s" builder definition', $param, $name)
                            );
                        }
                    }

                    if (in_array($param, array('object_actions', 'batch_actions'))) {
                        // Actions list comes from builder
                        $value = $configurations;
                    } else {
                        // All fields are still available in a builder
                        $value = array_merge($value ?:array(), $configurations);
                    }
                } else {
                    if (is_array($value)) {
                        $value = $this->recursiveReplace($value, $builder[$param]);
                    } else {
                        $value = $builder[$param];
                    }
                }
            }
        }

        // If builder doesn't have object|batc_actions remove it from merge.
        $global['object_actions'] = array_key_exists('object_actions', $builder) ? $global['object_actions'] : array();
        $global['batch_actions'] = array_key_exists('batch_actions', $builder) ? $global['batch_actions'] : array();

        return array_merge($global, array_diff_key($builder, $global));
    }

    /**
     * Merge configuration on a single level
     *
     * @param array $global
     * @param array $builder
     * @return array
     */
    protected function mergeConfiguration(array $global, array $builder)
    {
        foreach ($global as $name => &$value) {
            if (array_key_exists($name, $builder)) {
                if (is_null($builder[$name])) {
                    continue;
                }

                if (is_array($value)) {
                    if (!is_array($builder[$name])) {
                        throw new \InvalidArgumentException('Invalid generator');
                    }
                    $value = array_replace($value, $builder[$name]);
                } else {
                    $value = $builder[$name];
                }
            }
        }

        return array_merge($global, array_diff_key($builder, $global));
    }

    /**
     * Recursively replaces Base array values with Replacement array values
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
                if (is_array($value)) {
                    $value = (array_key_exists($key, $order) && is_array($order[$key]))
                        ? $replace_values_recursive($value, $order[$key])
                        : $value
                    ;
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
        $yaml_path = explode('.', $yaml_path);
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
