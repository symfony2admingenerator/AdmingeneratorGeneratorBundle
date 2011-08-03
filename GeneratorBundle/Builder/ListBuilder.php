<?php

namespace Admingenerator\GeneratorBundle\Builder;

/**
 * This builder generate php for lists actions
 * @author cedric Lombardot
 */
class ListBuilder extends BaseBuilder
{
	/**
	 * (non-PHPdoc)
	 * @see Builder/Admingenerator\GeneratorBundle\Builder.BaseBuilder::getYamlKey()
	 */
	public function getYamlKey()
	{
		return 'ListBuilder';
	}
}