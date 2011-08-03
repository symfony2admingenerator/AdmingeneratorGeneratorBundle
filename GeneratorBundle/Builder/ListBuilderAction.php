<?php

namespace Admingenerator\GeneratorBundle\Builder;

/**
 * This builder generate php for lists actions
 * @author cedric Lombardot
 */
class ListBuilderAction extends ListBuilder
{
	public function getOutputName()
	{
		return sprintf('%s/Controller/ListController.php', $this->getVariable('bundle_name'));
	}
}