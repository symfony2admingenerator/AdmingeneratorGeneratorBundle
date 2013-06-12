<?php

namespace Admingenerator\GeneratorBundle\Builder\Admin;

/**
 * This builder generates php for delete actions
 *
 * @author Stéphane Escandell
 */
class DeleteBuilderTemplate extends DeleteBuilder
{
    public function getOutputName()
    {
        return 'Resources/views/'.$this->getBaseGeneratorName().'Delete/index.html.twig';
    }

    /**
     * Prevent no BC Break
     *
     * @param array $variables
     */
    public function setVariables(array $variables)
    {
        if (!array_key_exists('title', $variables)) {
            $variables['title'] = 'Are you sure you want to delete this object?';
        }

        parent::setVariables($variables);
    }
}
