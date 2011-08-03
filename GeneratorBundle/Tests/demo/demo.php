#!/usr/bin/php
<?php

define('SFY_BASE_DIR',__DIR__.'/../../../../../' );
require_once SFY_BASE_DIR.'/app/bootstrap.php.cache';
require_once SFY_BASE_DIR.'app/AppKernel.php';
require_once SFY_BASE_DIR.'app/autoload.php';

use Admingenerator\GeneratorBundle\Builder\Generator;
use Admingenerator\GeneratorBundle\Builder\ListBuilderAction;
use Admingenerator\GeneratorBundle\Builder\ListBuilderTemplate;

$generator = new Generator(realpath(__DIR__).'/generator.yml');

$generator->addBuilder(new ListBuilderAction());
$generator->addBuilder(new ListBuilderTemplate());
$generator->writeOnDisk(SFY_BASE_DIR.$generator->getFromYaml('params.base_dir'));

/*
foreach ($generator->getBuilders() as $builder) {
	echo "GENERATE CODE FOR ".$builder->getSimpleClassName()."\n";
	echo $builder->getCode();
}
*/