<?php

namespace Admingenerator\GeneratorBundle\Form\Type;

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
            'builder'     => array('pk' => 'id', 'name' => 'name'),
            'matcher'     => 'item.name',
            'identifier'  => 'item.id',
            'description' => array(),
            'thumb'       => array('src' => null, 'width' => '32px', 'height' => '32px'),
        ));
        
        $resolver->setAllowedTypes(array(
            'builder'     => array('array'),
            'matcher'     => array('string'),
            'identifier'  => array('string'),
            'description' => array('array'),
            'thumb'       => array('array'),
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
        $view->vars['description'] = $options['description'];
        $view->vars['thumb'] = $options['thumb'];
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'entitypicker';
    }
}