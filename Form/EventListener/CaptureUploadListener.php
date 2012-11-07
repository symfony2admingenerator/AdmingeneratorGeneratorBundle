<?php

namespace Admingenerator\GeneratorBundle\Form\EventListener;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CaptureUploadListener implements EventSubscriberInterface
{
    /**
     * @var FormFactoryInterface
     */
    protected $factory;

    /**
     * @var string File class
     */
    protected $data_class;
    
    /**
     * @var array Uploaded files
     */
    protected $uploads;
    
    public function __construct(FormFactoryInterface $factory, $data_class)
    {
        $this->factory = $factory;
        $this->data_class = $data_class;
        $this->uploads = array();
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_BIND => array('preBind', 0),
            FormEvents::BIND => array('onBind', 0),
        );
    }

    public function preBind(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
        
        // capture uploads and store them for onBind event
        $this->uploads = $data['uploads'];
        // unset additional form data to prevent errors
        unset($data['uploads']);
        
        $event->setData($data);
    }
    
    public function onBind(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        $entity = $form->getParent()->getData();
        
        foreach($this->uploads as $upload) {
            if($upload === null) return;
            $file = new $this->data_class($upload, $entity);
            
            if (!($file instanceof \Admingenerator\GeneratorBundle\Model\FileInterface)) {
                throw new UnexpectedTypeException($file, '\Admingenerator\GeneratorBundle\Model\FileInterface');
            }
            
            $data->add($file);            
        }
        
        $event->setData($data);
    }
}