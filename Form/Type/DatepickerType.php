<?php

namespace Admingenerator\GeneratorBundle\Form\Type;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToLocalizedStringTransformer;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

use Symfony\Component\Form\Extension\Core\Type\DateType;

class DatepickerType extends DateType
{
    private static $acceptedFormats = array(
        \IntlDateFormatter::FULL,
        \IntlDateFormatter::LONG,
        \IntlDateFormatter::MEDIUM,
        \IntlDateFormatter::SHORT,
    );

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $emptyValue = $emptyValueDefault = function (Options $options) {
            return $options['required'] ? null : '';
        };

        $emptyValueNormalizer = function (Options $options, $emptyValue) use ($emptyValueDefault) {
            if (is_array($emptyValue)) {
                $default = $emptyValueDefault($options);

                return array_merge(
                    array('year' => $default, 'month' => $default, 'day' => $default),
                    $emptyValue
                );
            }

            return array(
                'year' => $emptyValue,
                'month' => $emptyValue,
                'day' => $emptyValue
            );
        };

        // BC until Symfony 2.3
        $modelTimezone = function (Options $options) {
            return $options['data_timezone'];
        };

        // BC until Symfony 2.3
        $viewTimezone = function (Options $options) {
            return $options['user_timezone'];
        };

        $resolver->setDefaults(array(
            'years'           => range(date('Y') - 5, date('Y') + 5),
            'months'          => range(1, 12),
            'days'            => range(1, 31),
            'prepend'         => false,
            'weekstart'       => 1,
            'autoclose'       => false,
            'widget'          => 'datepicker',
            'input'           => 'string',
            'format'          => self::HTML5_FORMAT,
            'model_timezone'  => $modelTimezone,
            'view_timezone'   => $viewTimezone,
            // Deprecated timezone options
            'data_timezone'   => null,
            'user_timezone'   => null,
            'empty_value'     => $emptyValue,
            // Don't modify \DateTime classes by reference, we treat
            // them like immutable value objects
            'by_reference'    => false,
            'error_bubbling'  => false,
            // If initialized with a \DateTime object, FormType initializes
            // this option to "\DateTime". Since the internal, normalized
            // representation is not \DateTime, but an array, we need to unset
            // this option.
            'data_class'      => null,
            'compound'        => false,
        ));

        $resolver->setNormalizers(array(
            'empty_value' => $emptyValueNormalizer,
        ));

        $resolver->setAllowedValues(array(
            'input'           =>  array('string'),
            'widget'          =>  array('datepicker'),
            'weekstart'       =>  range(0, 6),
        ));

        $resolver->setAllowedTypes(array(
            'format'    =>  array('int', 'string'),
            'prepend'   =>  array('bool', 'string'),
            'autoclose' =>  array('bool'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dateFormat = is_int($options['format']) ? $options['format'] : self::DEFAULT_FORMAT;
        $timeFormat = \IntlDateFormatter::NONE;
        $calendar = \IntlDateFormatter::GREGORIAN;
        $pattern = is_string($options['format']) ? $options['format'] : null;

        if (!in_array($dateFormat, self::$acceptedFormats, true)) {
            throw new InvalidOptionsException('The "format" option must be one of the IntlDateFormatter constants (FULL, LONG, MEDIUM, SHORT) or a string representing a custom format.');
        }

        if (null !== $pattern && (false === strpos($pattern, 'y') || false === strpos($pattern, 'M') || false === strpos($pattern, 'd'))) {
            throw new InvalidOptionsException(sprintf('The "format" option should contain the letters "y", "M" and "d". Its current value is "%s".', $pattern));
        }

        $builder->addViewTransformer(new DateTimeToLocalizedStringTransformer(
            $options['model_timezone'],
            $options['view_timezone'],
            $dateFormat,
            $timeFormat,
            $calendar,
            $pattern
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        parent::finishView($view, $form, $options);

        $view->vars['prepend'] = $options['prepend'];
        $view->vars['format'] = $options['format'];
        $view->vars['weekstart'] = $options['weekstart'];
        $view->vars['autoclose'] = $options['autoclose'] === true ? 'true' : 'false';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'datepicker';
    }
}
