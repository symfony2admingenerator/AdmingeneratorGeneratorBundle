<?php


namespace Admingenerator\GeneratorBundle\Features\Generator;

use Admingenerator\GeneratorBundle\Generator\DoctrineGenerator as BaseDoctrineGenerator;

class DoctrineGenerator extends BaseDoctrineGenerator
{
    public function setGeneratorYml($yaml_path)
    {
        $this->yaml_path = $yaml_path;
    }

    protected function getGeneratorYml()
    {
        return $this->yaml_path;
    }
}
