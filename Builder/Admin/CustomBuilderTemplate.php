<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generates php for custom actions
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class CustomBuilderTemplate extends CustomBuilder
{
    public function getOutputName()
    {
        return 'Resources/views/'.$this->getBaseGeneratorName().'Custom/index.html.twig';
    }
}
