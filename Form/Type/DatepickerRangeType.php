<?php

namespace Admingenerator\GeneratorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DatepickerRangeType extends AbstractType
{
    protected $defaults;

    public function __construct()
    {
        $this->defaults = array(
            'autoclose'     =>  true,
            'html5_format'  =>  'yyyy-MM-dd',
            'prepend_from'  =>  'date_range.from.label',
            'prepend_to'    =>  'date_range.to.label',
            'required'      =>  true,
            'weekstart'     =>  1,
            'years'         =>  range(date('Y'), date('Y') - 120),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
               ->add('from', new DatepickerType(), $options['from'])
               ->add('to', new DatepickerType(), $options['to']);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'form';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaults = $this->defaults;
        $resolver->setDefaults(
            array(
                'autoclose'   =>    null,
                'format'      =>    null,
                'prepend'     =>    null,
                'required'    =>    null,
                'weekstart'   =>    null,
                'years'       =>    null,
                'widget'      =>    'datepicker_range',
                'from'        =>    function (Options $options) use ($defaults) {
                    return array(
                        'widget'        =>  'datepicker',
                        'attr'          =>  array('class' => 'input-small'),
                        'autoclose'     =>  is_null($options['autoclose']) ? $defaults['autoclose']    : $options['autoclose'],
                        'format'        =>  is_null($options['format'])    ? $defaults['html5_format'] : $options['format'],
                        'prepend'       =>  is_null($options['prepend'])   ? $defaults['prepend_from'] : $options['prepend'],
                        'required'      =>  is_null($options['required'])  ? $defaults['required']     : $options['required'],
                        'weekstart'     =>  is_null($options['weekstart']) ? $defaults['weekstart']    : $options['weekstart'],
                        'years'         =>  is_null($options['years'])     ? $defaults['years']        : $options['years'],
                    );
                },
                'to'          =>    function (Options $options) use ($defaults) {
                    return array(
                        'widget'        =>  'datepicker',
                        'attr'          =>  array('class' => 'input-small'),
                        'autoclose'     =>  is_null($options['autoclose']) ? $defaults['autoclose']    : $options['autoclose'],
                        'format'        =>  is_null($options['format'])    ? $defaults['html5_format'] : $options['format'],
                        'prepend'       =>  is_null($options['prepend'])   ? $defaults['prepend_to']   : $options['prepend'],
                        'required'      =>  is_null($options['required'])  ? $defaults['required']     : $options['required'],
                        'weekstart'     =>  is_null($options['weekstart']) ? $defaults['weekstart']    : $options['weekstart'],
                        'years'         =>  is_null($options['years'])     ? $defaults['years']        : $options['years'],
                    );
                },
            )
        );

        $resolver->setAllowedValues(
            array(
                'weekstart'       =>  array_merge(array(null), range(0, 6)),
            )
        );

        $resolver->setAllowedTypes(
            array(
              'autoclose'       =>  array('null', 'bool'),
              'format'          =>  array('null', 'int', 'string'),
              'prepend'         =>  array('null', 'bool', 'string'),
              'required'        =>  array('null', 'bool'),
              'weekstart'       =>  array('null', 'int'),
              'years'           =>  array('null', 'array'),
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'datepicker_range';
    }
}
