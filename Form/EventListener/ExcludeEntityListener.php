<?php

namespace Admingenerator\GeneratorBundle\Form\EventListener;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ExcludeEntityListener implements EventSubscriberInterface
{
    /**
     * @var FormFactoryInterface
     */
    protected $factory;

    /**
     * @var string class
     */
    protected $class;
    
    public function __construct(FormFactoryInterface $factory, $class)
    {
        $this->factory = $factory;
        $this->class = $class;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::POST_SET_DATA => 'postSetData',
            FormEvents::PRE_BIND => 'preBind',
            FormEvents::BIND => 'onBind',
            FormEvents::POST_BIND => 'postBind',
        );
    }
    
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
        
        //var_dump($form); exit;
    }
    
    public function postSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
        
        //var_dump($form); exit;
    }

    public function preBind(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
        
        var_dump('preBind'); exit;
    }
    
    public function onBind(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        
        var_dump('onBind'); exit;
        
        //$event->setData($data);
    }
    
    public function postBind(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        
        var_dump('postBind'); exit;
        
        //$event->setData($data);
    }
}