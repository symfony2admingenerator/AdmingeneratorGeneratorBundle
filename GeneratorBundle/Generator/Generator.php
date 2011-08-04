<?php 

namespace Admingenerator\GeneratorBundle\Generator;

use Admingenerator\GeneratorBundle\Builder\Generator as AdminGenerator;
use Admingenerator\GeneratorBundle\Builder\ListBuilderAction;
use Admingenerator\GeneratorBundle\Builder\ListBuilderTemplate;

class Generator implements GeneratorInterface
{
	private $controller;
	
	private $action;
	
	const SFY_BASE_DIR = '/../../../../'; //Go to /
	
	public function setController($controller)
	{
		$this->controller = $controller[0];
		$this->action = $controller[1];
		
	}
	
	/**
	 * @todo use autoload or finder to find the good one
	 */
	protected function getGeneratorYml()
	{
		list($base, $bundle, $other ) = explode('\\',get_class($this->controller), 3);
		return realpath(__DIR__.self::SFY_BASE_DIR).DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.$base.DIRECTORY_SEPARATOR.$bundle.DIRECTORY_SEPARATOR.'Resources/config/generator.yml';
	}
	
	public function build()
	{
		$generator = new AdminGenerator($this->getGeneratorYml());

		$generator->addBuilder(new ListBuilderAction());
		$generator->addBuilder(new ListBuilderTemplate());
		$generator->writeOnDisk(realpath(__DIR__.self::SFY_BASE_DIR).DIRECTORY_SEPARATOR.$generator->getFromYaml('params.base_dir'));
		
	}
}