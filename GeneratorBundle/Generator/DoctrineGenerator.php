<?php

namespace Admingenerator\GeneratorBundle\Generator;

use Admingenerator\GeneratorBundle\Builder\Generator as AdminGenerator;
use Admingenerator\GeneratorBundle\Builder\Doctrine\ListBuilderAction;
use Admingenerator\GeneratorBundle\Builder\Doctrine\ListBuilderTemplate;

class DoctrineGenerator extends Generator
{
    public function build()
    {
        $generator = new AdminGenerator($this->getGeneratorYml());

        $generator->addBuilder(new ListBuilderAction());
        $generator->addBuilder(new ListBuilderTemplate());
        $generator->writeOnDisk(realpath(__DIR__.self::SFY_BASE_DIR).DIRECTORY_SEPARATOR.$generator->getFromYaml('params.base_dir'));
    }
}