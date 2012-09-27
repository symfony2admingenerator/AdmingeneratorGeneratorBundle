<?php

namespace Admingenerator\GeneratorBundle\Form\Type;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Bridge\Doctrine\Form\EventListener\MergeDoctrineCollectionListener;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EntityPickerType extends EntityType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        
        $resolver->setDefaults(array(
            'builder'     =>  array(),
            'matcher'     => 'item',
            'identifier'  => 'item',
            'placeholder' => false,
        ));
        
        $resolver->setAllowedTypes(array(
            'builder'     => array('array'),
            'matcher'     => array('string'),
            'identifier'  => array('string'),
            'placeholder'  => array('boolean', 'string'),
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['builder'] = $options['builder'];
        $view->vars['matcher'] = $options['matcher'];
        $view->vars['identifier'] = $options['identifier'];
        $view->vars['placeholder'] = $options['placeholder'];
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'entity_picker';
    }
}
