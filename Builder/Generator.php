<?php

namespace Admingenerator\GeneratorBundle\Builder;

/**
 * @author Cedric LOMBARDOT
 */


use Symfony\Component\Yaml\Yaml;

class Generator
{
    const TEMP_DIR_PREFIX = 'Admingenerator';

    /**
     * @var string the temporary dir
     */
    protected $tempDir;

    /**
     * @var array List of builders
     */
    protected $builders = array();

    /**
     * @var file $yaml the yaml
     */
    protected $yaml;

    protected $mustOverwriteIfExists = false;

    protected $templateDirectories = array();

    protected $baseController;

    protected $columnClass = 'Admingenerator\GeneratorBundle\Generator\Column';

    protected $base_admin_template = 'AdmingeneratorGeneratorBundle::base_admin.html.twig';

    protected $base_generator_name;

    /**
     * Init a new generator and automatically define the base of tempDir
     *
     * @param Filepath $yaml
     */
    public function __construct($yaml)
    {
        $this->tempDir = realpath(sys_get_temp_dir()).DIRECTORY_SEPARATOR.self::TEMP_DIR_PREFIX;
        if (!is_dir($this->tempDir)) {
            mkdir($this->tempDir, 0777, true);
        }
        $this->setYamlConfig(Yaml::parse($yaml));
    }

    public function setMustOverwriteIfExists($status = true)
    {
        $this->mustOverwriteIfExists = $status;
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
     * (non-PHPdoc)
     * @see Builder/Admingenerator\GeneratorBundle\Builder.BuilderInterface::setTemplateDirs()
     */
    public function setTemplateDirs(array $templateDirs)
    {
        $this->templateDirectories = $templateDirs;
    }


    /**
     * Ensure to remove tempDir
     */
    public function __destruct()
    {
        if ($this->tempDir && is_dir($this->tempDir)) {
            $this->removeDir($this->tempDir);
        }
    }

    /**
     * @return string the $tempDir
     */
    public function getTempDir()
    {
        return $this->tempDir;
    }

    /**
     * @return array the list of builders
     */
    public function getBuilders()
    {
        return $this->builders;
    }

    /**
     * Add a builder
     * @param BuilderInterface $builder
     */
    public function addBuilder(BuilderInterface $builder)
    {
        $builder->setGenerator($this);
        $builder->setTemplateDirs($this->templateDirectories);
        $builder->setMustOverwriteIfExists($this->mustOverwriteIfExists);
        $builder->setColumnClass($this->getColumnClass());

        $vars = array_replace_recursive(
            $this->getFromYaml('params', array()),
            $this->getFromYaml(sprintf('builders.%s.params', $builder->getYamlKey()), array())
        );

        $builder->setVariables($vars);

        $this->builders[$builder->getSimpleClassName()] = $builder;
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
     * Generated and write classes to disk
     *
     * @param string $outputDirectory
     * @param array  $variables
     */
    public function writeOnDisk($outputDirectory)
    {
        foreach ($this->builders as $builder) {
            $builder->writeOnDisk($outputDirectory);
        }
    }

    /**
     * Remove a directory
     * @param string $target
     */
    private function removeDir($target)
    {
        $fp = opendir($target);
        while (false !== $file = readdir($fp)) {
            if (in_array($file, array('.', '..'))) {
                continue;
            }

            if (is_dir($target.'/'.$file)) {
                self::removeDir($target.'/'.$file);
            } else {
                unlink($target.'/'.$file);
            }
        }
        closedir($fp);
        rmdir($target);
    }

    /**
     * Set the yaml to pass all the vars to the builders
     * @param Yaml $yaml
     */
    protected function setYamlConfig(array $yaml)
    {
        $this->yaml = $yaml;
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

}
