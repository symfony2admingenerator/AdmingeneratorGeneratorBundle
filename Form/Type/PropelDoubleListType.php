<?php

namespace Admingenerator\GeneratorBundle\Form\Type;


use Symfony\Component\Validator\Constraints\ChoiceValidator;

use Symfony\Component\Form\Extension\Core\DataTransformer\ArrayToChoicesTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Propel\PropelBundle\Form\ChoiceList\ModelChoiceList;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

use Propel\PropelBundle\Form\EventListener\MergeCollectionListener;
use Propel\PropelBundle\Form\DataTransformer\ModelsToArrayTransformer;
use Propel\PropelBundle\Form\DataTransformer\ModelToIdTransformer;

class PropelDoubleListType extends AbstractType
{

    protected $choices;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
         $builder
               ->prependClientTransformer(new ModelsToArrayTransformer($options['choice_list']))
               ;

        $this->choices = $options['choice_list']->getChoices();

        unset($options['choices']);

    }


    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $values = $view->get('value');

        $selecteds = array_flip($values);
        $choices_selected = $choices_unselected = array();

        //Rebuilds choices
        foreach ($this->choices as $key => $choice) {
            if (isset($selecteds[$key])) {
                $choices_selected[$key] = $choice;
            } else {
                $choices_unselected[$key] = $choice;
            }
        }

        $view->set('choices_selected', $choices_selected);
        $view->set('choices_unselected', $choices_unselected);
    }

    public function getParent(array $options)
    {
        return 'field';
    }

    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'class'             => null,
            'property'          => null,
            'choices'           => array(),
            'query_object'      => null,
        );

        $options = array_replace($defaultOptions, $options);

        if (!isset($options['choice_list'])) {
            $defaultOptions['choice_list'] = new ModelChoiceList(
                $options['class'],
                $options['property'],
                $options['choices'],
                $options['query_object']
            );
        }

        return $defaultOptions;
    }

    public function getName()
    {
        return 'propel_double_list';
    }
}
