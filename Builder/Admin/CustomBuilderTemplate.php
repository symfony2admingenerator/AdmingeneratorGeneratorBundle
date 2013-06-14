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

    /**
     * Prevent no BC Break
     *
     * @param array $variables
     */
    public function setVariables(array $variables)
    {
        if (!array_key_exists('title', $variables)) {
            $variables['title'] = 'Are you sure?';
        }

        parent::setVariables($variables);
    }
}
