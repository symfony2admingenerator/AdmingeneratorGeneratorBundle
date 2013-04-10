<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

use Admingenerator\GeneratorBundle\Generator\Action;

/**
 * This builder generates php for show actions
 *
 * @author Eymen Gunay
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class ShowBuilder extends BaseBuilder
{
    /**
     * (non-PHPdoc)
     * @see Admingenerator\GeneratorBundle\Builder.BaseBuilder::getYamlKey()
     */
    public function getYamlKey()
    {
        return 'show';
    }

    /**
     * Find form actions
     */
    protected function findActions()
    {
        foreach ($this->getVariable('actions', array()) as $actionName => $actionParams) {
            $action = $this->findGenericAction($actionName);
            if(!$action) $action = $this->findObjectAction($actionName);
            if(!$action) $action = new Action($actionName);

            $this->setUserActionConfiguration($action);
            $this->addAction($action);
        }
    }
}
