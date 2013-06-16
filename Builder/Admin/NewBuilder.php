<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

use Admingenerator\GeneratorBundle\Generator\Action;

/**
 * This builder generates php for new actions
 *
 * @author cedric Lombardot
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class NewBuilder extends BaseBuilder
{
    /**
     * (non-PHPdoc)
     * @see Admingenerator\GeneratorBundle\Builder.BaseBuilder::getYamlKey()
     */
    public function getYamlKey()
    {
        return 'new';
    }

    /**
     * Find form actions
     */
    protected function findActions()
    {
        $actions = $this->getVariable('actions', array());

        foreach ($actions as $actionName => $actionParams) {
            $action = $this->findGenericAction($actionName);
            
            if(!$action) {
                $action = $this->findObjectAction($actionName);
            }
            
            if(!$action) {
                $action = new Action($actionName);
            }

            $this->setUserActionConfiguration($action);
            $this->addAction($action);
        }
    }
}
