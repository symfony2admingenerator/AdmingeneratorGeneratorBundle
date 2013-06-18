<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generates php for custom actions
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class ActionsBuilderTemplate extends ActionsBuilder
{
    public function getOutputName()
    {
        return 'Resources/views/'.$this->getBaseGeneratorName().'Actions/index.html.twig';
    }
}
