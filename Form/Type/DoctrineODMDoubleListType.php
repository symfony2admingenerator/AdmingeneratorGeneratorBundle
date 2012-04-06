<?php

namespace Admingenerator\GeneratorBundle\Form\Type;

use Symfony\Bundle\DoctrineMongoDBBundle\Form\Type\DocumentType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class DoctrineODMDoubleListType extends DocumentType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $choiceList = $form->getAttribute('choice_list');

        $choices = $choiceList->getChoices();
        $indices =  $choiceList->getValuesForChoices($choices);
        $selectedChoices = $choiceList->getChoicesForValues($view->get('value'));
        $selectedIndices =  $choiceList->getValuesForChoices($selectedChoices);

        $choices_selected = $choices_unselected = array();

        foreach ($indices as $k => $indice) {
            if (in_array($indice, $selectedIndices)) {
                $choices_selected[] = array(
                    'value' => $indice,
                    'label' => $choices[$indice]
                );
            } else {
                $choices_unselected[] = array(
                    'value' => $indice,
                    'label' => $choices[$indice]
                );
            }
        }

        $view->set('choices_selected',  $choices_selected);
        $view->set('choices_unselected', $choices_unselected);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $options = parent::getDefaultOptions($options);
        $options['multiple'] = true;

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'doctrine_odm_double_list';
    }
}
