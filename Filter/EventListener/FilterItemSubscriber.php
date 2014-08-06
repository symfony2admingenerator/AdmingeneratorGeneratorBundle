<?php

namespace Admingenerator\GeneratorBundle\Filter\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class FilterItemSubscriber implements EventSubscriberInterface
{
    protected $filters;

    protected $fieldName;
    
    /**
     * @param array         $filters   An array of filter configurations.
     * @param string|null   $fieldName Form field name.
     */
    public function __construct(array $filters, $fieldName = null)
    {
        $this->filters      = $filters;
        $this->fieldName    = $fieldName;
    }
    
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA    => 'preSetData',
            FormEvents::POST_SET_DATA   => 'postSetData',
            FormEvents::PRE_SUBMIT      => 'preSubmit'
        );
    }

    /**
     * Builds form based on field name passed in constructor.
     * 
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null !== $this->fieldName) {
            $this->addFilterFields($form);
        }
    }

    /**
     * Builds form based on field name passed in set data.
     * 
     * @param FormEvent $event
     */
    public function postSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $this->fieldName && null !== $data['field']) {
            $this->fieldName = $data['field'];
            $this->addFilterFields($form);
        }
    }

    /**
     * Builds form based on field name passed in submitted data.
     * 
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $this->fieldName && null !== $data['field']) {
            $this->fieldName = $data['field'];
            $this->addFilterFields($form, $config);
        }
    }

    /**
     * Adds filter fields to form.
     * 
     * @param FormInterface $form   The filter form.
     */
    private function addFilterFields(FormInterface $form)
    {
        $config = $this->getConfig($this->fieldName);

        $form->add('field', 'hidden', array('data' => $config['field']));
        $form->add('operator', 'choice', array(
            'choices'               => $this->getOperators($config['filter']),
            'required'              => true,
            'translation_domain'    => 'Admingenerator'
        ));

        $isCollectionType = array_key_exists('type', $config['options'])
            && array_key_exists('options', $config['options'])
            && array_key_exists('prototype', $config['options']);

        if ($isCollectionType) {
            $formType = $config['options']['type'];
            $formOptions = $config['options']['options'];
        } else {
            $formType = $config['form'];
            $formOptions = $config['options'];
        }

        $form->add('value', $formType, array_merge(
            $formOptions,
            array('required' => true)
        ));
    }

    /**
     * Get config for field
     * 
     * @param  string $name Field name
     * @return array
     */
    private function getConfig($name)
    {
        return $this->filters[$name];
    }
    
    /**
     * Get operators.
     * 
     * @return array
     */
    private function getOperators($filter)
    {
        switch ($filter) {
            case 'boolean':
                return array(
                    'Equal'             => 'filters.boolean.equal'
                );
            case 'date':
                return array(
                    'Equal'             => 'filters.date.equal',
                    'NotEqual'          => 'filters.date.not_equal',
                    'GreaterThan'       => 'filters.date.greater_than',
                    'GreaterThanEqual'  => 'filters.date.greater_than_equal',
                    'LessThan'          => 'filters.date.less_than',
                    'LessThanEqual'     => 'filters.date.less_than_equal'
                );
            case 'time':
                return array(
                    'Equal'             => 'filters.time.equal',
                    'NotEqual'          => 'filters.time.not_equal',
                    'GreaterThan'       => 'filters.time.greater_than',
                    'GreaterThanEqual'  => 'filters.time.greater_than_equal',
                    'LessThan'          => 'filters.time.less_than',
                    'LessThanEqual'     => 'filters.time.less_than_equal'
                );
            case 'datetime':
                return array(
                    'Equal'             => 'filters.datetime.equal',
                    'NotEqual'          => 'filters.datetime.not_equal',
                    'GreaterThan'       => 'filters.datetime.greater_than',
                    'GreaterThanEqual'  => 'filters.datetime.greater_than_equal',
                    'LessThan'          => 'filters.datetime.less_than',
                    'LessThanEqual'     => 'filters.datetime.less_than_equal'
                );
            case 'number':
                return array(
                    'Equal'             => 'filters.number.equal',
                    'NotEqual'          => 'filters.number.not_equal',
                    'GreaterThan'       => 'filters.number.greater_than',
                    'GreaterThanEqual'  => 'filters.number.greater_than_equal',
                    'LessThan'          => 'filters.number.less_than',
                    'LessThanEqual'     => 'filters.number.less_than_equal'
                );
            case 'text':
                return array(
                    'Equal'             => 'filters.text.equal',
                    'NotEqual'          => 'filters.text.not_equal',
                    'Like'              => 'filters.text.like',
                    'NotLike'           => 'filters.text.not_like'
                );
            case 'array':
                return array(
                    'Like'              => 'filters.array.like',
                    'NotLike'           => 'filters.array.not_like'
                );
            case 'collection':
                return array(
                    'Contains'          => 'filters.collection.contains',
                    'NotContains'       => 'filters.collection.not_contains'
                );
            case 'model':
                return array(
                    'Equal'             => 'filters.model.equal',
                    'NotEqual'          => 'filters.model.not_equal'
                );
            default:
                return array();
        }
    }
}