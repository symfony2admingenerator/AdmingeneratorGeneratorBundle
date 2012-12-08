<?php

namespace Admingenerator\GeneratorBundle\Generator;

use Admingenerator\GeneratorBundle\Builder\Generator as AdminGenerator;

use Admingenerator\GeneratorBundle\Builder\Doctrine\ListBuilderAction;
use Admingenerator\GeneratorBundle\Builder\Doctrine\ListBuilderTemplate;
use Admingenerator\GeneratorBundle\Builder\Doctrine\FiltersBuilderType;

use Admingenerator\GeneratorBundle\Builder\Doctrine\DeleteBuilderAction;

use Admingenerator\GeneratorBundle\Builder\Doctrine\EditBuilderAction;
use Admingenerator\GeneratorBundle\Builder\Doctrine\EditBuilderTemplate;
use Admingenerator\GeneratorBundle\Builder\Doctrine\EditBuilderType;

use Admingenerator\GeneratorBundle\Builder\Doctrine\NewBuilderAction;
use Admingenerator\GeneratorBundle\Builder\Doctrine\NewBuilderTemplate;
use Admingenerator\GeneratorBundle\Builder\Doctrine\NewBuilderType;

class DoctrineGenerator extends Generator
{
    /**
     * (non-PHPdoc)
     * @see Generator/Admingenerator\GeneratorBundle\Generator.Generator::build()
     * @see http://juliusbeckmann.de/blog/php-benchmark-isset-or-array_key_exists.html
     *   // Key is NULL
     *   $array['key'] = NULL;
     *   var_dump(isset($array['key'])); // false
     *   var_dump(array_key_exists('key', $array)); // true
     */
    public function build()
    {
        $this->validateYaml();
        
        $generator = new AdminGenerator($this->cache_dir, $this->getGeneratorYml());
        $generator->setContainer($this->container);
        $generator->setBaseAdminTemplate($this->container->getParameter('admingenerator.base_admin_template'));
        $generator->setFieldGuesser($this->getFieldGuesser());
        $generator->setMustOverwriteIfExists($this->needToOverwrite($generator));
        $generator->setTemplateDirs(array_merge(
            $this->container->getParameter('admingenerator.doctrine_templates_dirs'),
            array(__DIR__.'/../Resources/templates/Doctrine')
        ));
        $generator->setBaseController('Admingenerator\GeneratorBundle\Controller\Doctrine\BaseController');
        $generator->setBaseGeneratorName($this->getBaseGeneratorName());

        $builders = $generator->getFromYaml('builders',array());

        if (array_key_exists('list',$builders)) {
            $generator->addBuilder(new ListBuilderAction());
            $generator->addBuilder(new ListBuilderTemplate());
            $generator->addBuilder(new FiltersBuilderType());
        }

        if (array_key_exists('delete', $builders)) {
            $generator->addBuilder(new DeleteBuilderAction());
        }

        if (array_key_exists('edit', $builders)) {
            $generator->addBuilder(new EditBuilderAction());
            $generator->addBuilder(new EditBuilderTemplate());
            $generator->addBuilder(new EditBuilderType());
        }

        if (array_key_exists('new', $builders)) {
            $generator->addBuilder(new NewBuilderAction());
            $generator->addBuilder(new NewBuilderTemplate());
            $generator->addBuilder(new NewBuilderType());
        }

        $generator->writeOnDisk($this->getCachePath($generator->getFromYaml('params.namespace_prefix'), $generator->getFromYaml('params.bundle_name')));
    }
}
