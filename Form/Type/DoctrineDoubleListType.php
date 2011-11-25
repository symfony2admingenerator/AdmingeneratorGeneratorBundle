<?php

namespace Admingenerator\GeneratorBundle\Form\Type;


use Symfony\Component\Validator\Constraints\ChoiceValidator;

use Symfony\Component\Form\Extension\Core\DataTransformer\ArrayToChoicesTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;

use Symfony\Bridge\Doctrine\RegistryInterface;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

use Symfony\Bridge\Doctrine\Form\EventListener\MergeCollectionListener;
use Symfony\Bridge\Doctrine\Form\DataTransformer\EntitiesToArrayTransformer;
use Symfony\Bridge\Doctrine\Form\DataTransformer\EntityToIdTransformer;

class DoctrineDoubleListType extends AbstractType
{

    protected $registry;

    protected $choices;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
         $builder
               ->prependClientTransformer(new EntitiesToArrayTransformer($options['choice_list']))
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
            'em'                => null,
            'class'             => null,
            'property'          => null,
            'query_builder'     => null,
            'choices'           => array(),
        );

        $options = array_replace($defaultOptions, $options);

        if (!isset($options['choice_list'])) {
            $defaultOptions['choice_list'] = new EntityChoiceList(
                $this->registry->getEntityManager($options['em']),
                $options['class'],
                $options['property'],
                $options['query_builder'],
                $options['choices']
            );
        }

        return $defaultOptions;
    }

    public function getName()
    {
        return 'doctrine_double_list';
    }
}
