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
         
        $vars = array_merge(
        $this->getFromYaml(sprintf('builders.%s.params', $builder->getYamlKey()), array()),
        $this->getFromYaml('params', array())
        );
         
        $builder->setVariables($vars);

        $this->builders[$builder->getSimpleClassName()] = $builder;
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
}
