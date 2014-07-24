<?php

namespace Admingenerator\GeneratorBundle\Filter\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class FilterRootType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'type'              => 'admingenerator_filter_group',
            'allow_add'         => true,
            'allow_delete'      => true,
            'prototype'         => true,
            'prototype_name'    => '__filter_group_name__',
            'options'           => array(
                'filters' => array()
            )
        ));
        
        $resolver->setAllowedValues(array(
            'type'          => array('admingenerator_filter_group'),
            'allow_add'     => array(true),
            'allow_delete'  => array(true),
            'prototype'     => array(true)
        ));
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $filters = $options['options']['filters'];
        $filterPrototypeName = '__filter_form_name__';
        $filterOptions = array('label' => $filterPrototypeName.'label__');

        // build filter prototypes
        $builder->setAttribute('prototypes', $this->getPrototypeForms(
            $builder,
            $filters,
            $filterPrototypeName,
            $filterOptions
        ));
    }
    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // the "prototype" var holds group form prototype
        // while the "prototypes" var is an associative array
        // of fieldName => filterPrototype pairs
        $view->vars['prototypes'] = array_map(
            function($form) use ($view) {
                return $form->createView($view);
            }, 
            $form->getConfig()->getAttribute('prototypes')
        );
    }

    /**
     * Get prototype forms. 
     *
     * @param FormBuilderInterface  $builder    The builder.
     * @param FilterConfig[]        $filters    An array of FilterConfig instances.
     * @param string                $name       Prototype name.
     * @param array                 $options    Prototype options.
     *
     * @return FormInterface[]      An array of FormInterface instances.
     */
    private function getPrototypeForms(FormBuilderInterface $builder, array $filters, $name, $options)
    {
        $forms = array();

        foreach ($filters as $field => $filter) {
            $prototype = $builder->create($name, new FilterFormType($filter), $options);
            $forms[$field] = $prototype->getForm();
        }

        return $forms;
    }
    
    public function getParent()
    {
        return 'collection';
    }

    public function getName()
    {
        return 'admingenerator_filter_root';
    }
}
