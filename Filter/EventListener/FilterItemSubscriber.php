<?php

namespace Admingenerator\GeneratorBundle\Filter\EventListener;

use Admingenerator\GeneratorBundle\Filter\FilterConfig;
use Admingenerator\GeneratorBundle\Filter\Type\FilterFormType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class FilterItemSubscriber implements EventSubscriberInterface
{
    protected $filterConfigs;
    
    /**
     * @param FilterConfig[] $filterConfigsAn array FilterConfig instances with fieldName keys for easy access.
     */
    public function __construct(array $filterConfigs = array())
    {
        $this->filterConfigs = $filterConfigs;
    }
    
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    /**
     * Parses submitted data and dynamically builds filters form.
     * 
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
        
        if ($data && array_key_exists('operator', $data)) {
            $form->add('operator', 'admingenerator_filter_logical_operator');
        }
        
        if ($data && array_key_exists('group', $data)) {
            $form->add('group', 'admingenerator_filter_group', array(
                'options' => array('filterConfigs' => $this->filterConfigs)
            ));
        }
        
        if ($data && array_key_exists('field', $data) && array_key_exists('filter', $data)) {
            $field = $data['field'];
            $fieldNames = array_keys($this->filterConfigs);
            
            /**
             * Why not throw an error here?
             * 
             * If the submitted field name is invalid it, no fields will be added,
             * therefore the form will invalidate due to unexpected extra data.
             */
            if (in_array($field, $fieldNames)) {
                $form->add('field', 'choice', array('choices' => $fieldNames));
                $form->add('filter', new FilterFormType($this->filterConfigs[$field]));
            }
        }
    }
}