<?php

namespace Admingenerator\GeneratorBundle\Form\Type;

use Symfony\Bridge\Propel1\Form\Type\ModelType;
use Symfony\Component\Form\FormViewInterface;
use Symfony\Component\Form\FormInterface;

class PropelDoubleListType extends ModelType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormViewInterface $view, FormInterface $form, array $options)
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
    public function getDefaultOptions()
    {
        return array_merge(parent::getDefaultOptions(), array(
            'multiple'  => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'propel_double_list';
    }
}
