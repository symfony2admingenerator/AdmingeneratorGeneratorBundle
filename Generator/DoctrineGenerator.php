<?php

namespace Admingenerator\GeneratorBundle\Generator;

use Admingenerator\GeneratorBundle\Builder\Generator as AdminGenerator;
use Admingenerator\GeneratorBundle\Exception\CantGenerateException;

use Admingenerator\GeneratorBundle\Builder\Doctrine\ListBuilderAction;
use Admingenerator\GeneratorBundle\Builder\Doctrine\ListBuilderTemplate;
use Admingenerator\GeneratorBundle\Builder\Doctrine\NestedListBuilderAction;
use Admingenerator\GeneratorBundle\Builder\Doctrine\NestedListBuilderTemplate;
use Admingenerator\GeneratorBundle\Builder\Doctrine\FiltersBuilderType;

use Admingenerator\GeneratorBundle\Builder\Doctrine\DeleteBuilderAction;

use Admingenerator\GeneratorBundle\Builder\Doctrine\EditBuilderAction;
use Admingenerator\GeneratorBundle\Builder\Doctrine\EditBuilderTemplate;
use Admingenerator\GeneratorBundle\Builder\Doctrine\EditBuilderType;

use Admingenerator\GeneratorBundle\Builder\Doctrine\NewBuilderAction;
use Admingenerator\GeneratorBundle\Builder\Doctrine\NewBuilderTemplate;
use Admingenerator\GeneratorBundle\Builder\Doctrine\NewBuilderType;

use Admingenerator\GeneratorBundle\Builder\Doctrine\ShowBuilderAction;
use Admingenerator\GeneratorBundle\Builder\Doctrine\ShowBuilderTemplate;

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
        $generator->setBaseAdminTemplate($generator->getFromYaml('base_admin_template', $this->container->getParameter('admingenerator.base_admin_template')));
        $generator->setFieldGuesser($this->getFieldGuesser());
        $generator->fieldGuesser->setEntityManager($generator->getFromYaml('params.entity_manager', null));
        $generator->setMustOverwriteIfExists($this->needToOverwrite($generator));
        $generator->setTemplateDirs(array_merge(
            $this->container->getParameter('admingenerator.doctrine_templates_dirs'),
            array(__DIR__.'/../Resources/templates/Doctrine')
        ));
        $generator->setBaseController('Admingenerator\GeneratorBundle\Controller\Doctrine\BaseController');
        $generator->setBaseGeneratorName($this->getBaseGeneratorName());

        $embed_types = $generator->getFromYaml('params.embed_types', array());

        foreach ($embed_types as $yaml_path) {
            $this->prebuildEmbedType($yaml_path, $generator);
        }

        $builders = $generator->getFromYaml('builders',array());

        if (array_key_exists('list',$builders)) {
            $generator->addBuilder(new ListBuilderAction());
            $generator->addBuilder(new ListBuilderTemplate());
            $generator->addBuilder(new FiltersBuilderType());
        }

        if (array_key_exists('nested_list',$builders)) {
            $generator->addBuilder(new NestedListBuilderAction());
            $generator->addBuilder(new NestedListBuilderTemplate());
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

        if (array_key_exists('show', $builders)) {
            $generator->addBuilder(new ShowBuilderAction());
            $generator->addBuilder(new ShowBuilderTemplate());
        }

        $generator->writeOnDisk($this->getCachePath($generator->getFromYaml('params.namespace_prefix'), $generator->getFromYaml('params.bundle_name')));
    }

    public function prebuildEmbedType($yaml_path, $generator)
    {
        $pattern_string = '/(?<namespace_prefix>(?>.+\:)?.+)\:(?<bundle_name>.+Bundle)\:(?<generator_path>.*?)$/';

        if (preg_match($pattern_string, $yaml_path, $match_string)) {
            $namespace_prefix = $match_string['namespace_prefix'];
            $bundle_name      = $match_string['bundle_name'];
            $generator_path   = $match_string['generator_path'];
        } else {
            $namespace_prefix = $generator->getFromYaml('params.namespace_prefix');
            $bundle_name      = $generator->getFromYaml('params.bundle_name');
            $generator_path   = $yaml_path;
        }

        $kernel = $this->container->get('kernel');
        $yaml_file = $kernel->locateResource('@'.$namespace_prefix.$bundle_name.'/Resources/config/'.$generator_path);

        if (!file_exists($yaml_file)) {
            throw new CantGenerateException("Can't generate embed type for $yaml_file, file not found.");
        }

        $embedGenerator = new AdminGenerator($this->cache_dir, $yaml_file);
        $embedGenerator->setContainer($this->container);
        $embedGenerator->setBaseAdminTemplate($embedGenerator->getFromYaml('base_admin_template', $this->container->getParameter('admingenerator.base_admin_template')));
        $embedGenerator->setFieldGuesser($this->getFieldGuesser());
        $embedGenerator->fieldGuesser->setEntityManager($generator->getFromYaml('params.entity_manager', null));
        $embedGenerator->setMustOverwriteIfExists($this->needToOverwrite($embedGenerator));
        $embedGenerator->setTemplateDirs(array_merge(
            $this->container->getParameter('admingenerator.doctrine_templates_dirs'),
            array(__DIR__.'/../Resources/templates/Doctrine')
        ));

        $embedGenerator->addBuilder(new EditBuilderType());

        $embedGenerator->writeOnDisk($this->getCachePath($embedGenerator->getFromYaml('params.namespace_prefix'), $generator->getFromYaml('params.bundle_name')));
    }
}
