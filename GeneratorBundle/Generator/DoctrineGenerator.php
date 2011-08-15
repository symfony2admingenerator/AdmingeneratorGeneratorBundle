<?php

namespace Admingenerator\GeneratorBundle\Generator;

use Admingenerator\GeneratorBundle\Builder\Generator as AdminGenerator;
use Admingenerator\GeneratorBundle\Builder\Doctrine\ListBuilderAction;
use Admingenerator\GeneratorBundle\Builder\Doctrine\ListBuilderTemplate;

class DoctrineGenerator extends Generator
{
    /**
     * (non-PHPdoc)
     * @see Generator/Admingenerator\GeneratorBundle\Generator.Generator::build()
     */
    public function build()
    {
        $generator = new AdminGenerator($this->getGeneratorYml());

        $builders = $generator->getFromYaml('builders',array());

        if(isset($builders['list'])) {
            $generator->addBuilder(new ListBuilderAction());
            $generator->addBuilder(new ListBuilderTemplate());
        }

        $generator->writeOnDisk($this->getCachePath($generator->getFromYaml('params.namespace_prefix'), $generator->getFromYaml('params.bundle_name')));
    }
}