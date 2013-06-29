<?php

namespace Admingenerator\GeneratorBundle\Form\EventListener;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CaptureUploadListener implements EventSubscriberInterface
{
    /**
     * @var string Property edited with UploadType
     */
    protected $propertyName;

    /**
     * @var string UploadType data_class
     */
    protected $dataClass;

    /**
     * @var string Upload class nameable field
     */
    protected $nameable;

    /**
     * Used to revert changes if form is not valid.
     * @var Doctrine\Common\Collections\Collection Original collection
     */
    protected $originalFiles;

    /**
     * @var array Captured upload
     */
    protected $uploads;

    public function __construct($propertyName, $dataClass, $nameable)
    {
        $this->propertyName = $propertyName;
        $this->dataClass    = $dataClass;
        $this->nameable     = $nameable;
        $this->uploads      = array();
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SUBMIT => array('preSubmit', 0),
            FormEvents::SUBMIT => array('onSubmit', 0),
            FormEvents::POST_SUBMIT => array('postSubmit', 0),
        );
    }

    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (array_key_exists('uploads', $data)) {
            // capture uploads and store them for onBind event
            $this->uploads = $data['uploads'];
            // unset additional form data to prevent errors
            unset($data['uploads']);
        }

        $event->setData($data);
    }

    public function onSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $form->getParent()->getData();

        // save original files collection for postBind event
        $getter = 'get'.ucfirst($this->propertyName);
        $this->originalFiles = $data->$getter();

        // create file entites for each file
        foreach ($this->uploads as $upload) {
            if($upload === null) return;

            $file = new $this->dataClass();
            if (!$file instanceof \Admingenerator\GeneratorBundle\Model\FileInterface) {
                throw new UnexpectedTypeException($file, '\Admingenerator\GeneratorBundle\Model\FileInterface');
            }

            $file->setFile($upload);
            $file->setParent($data);

            // if nameable field specified - set normalized name
            if ($this->nameable) {
                $setNameable = 'set'.ucfirst($this->nameable);

                // this value is unsafe
                $name = $upload->getClientOriginalName();
                // normalize string
                $safeName = $this->normalizeUtf8String($name);

                $file->$setNameable($safeName);
            }
            $data->$getter()->add($file);
        }

        $event->setData($data->$getter());
    }

    public function postSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $form->getParent()->getData();

        $getter = 'get'.ucfirst($this->propertyName);
        if (!$form->isValid() && $data->$getter() instanceof ArrayCollection) {
            // remove files absent in the original collection
            $data->$getter()->clear();

            foreach ($this->originalFiles as $file) {
                $data->$getter()->add($file);
            }
            // TODO: find a way to restore $this->uploads to the form
            $event->setData($data->$getter());
        }
    }

    private function normalizeUtf8String($s)
    {
        // save original string
        $original_string = $s;

        // Normalizer-class missing!
        if (!class_exists('\Normalizer')) {
            // remove all non-whitelisted characters
            return  preg_replace( '@[^a-zA-Z0-9._\-\s ]@u' , "", $original_string );
        }

        $normalizer = new \Normalizer();

        // maps German (umlauts) and other European characters onto two characters before just removing diacritics
        $s = preg_replace('@\x{00c4}@u', "AE", $s);    // umlaut Ä => AE
        $s = preg_replace('@\x{00d6}@u', "OE", $s);    // umlaut Ö => OE
        $s = preg_replace('@\x{00dc}@u', "UE", $s);    // umlaut Ü => UE
        $s = preg_replace('@\x{00e4}@u', "ae", $s);    // umlaut ä => ae
        $s = preg_replace('@\x{00f6}@u', "oe", $s);    // umlaut ö => oe
        $s = preg_replace('@\x{00fc}@u', "ue", $s);    // umlaut ü => ue
        $s = preg_replace('@\x{00f1}@u', "ny", $s);    // ñ => ny
        $s = preg_replace('@\x{00ff}@u', "yu", $s);    // ÿ => yu

        // maps special characters (characters with diacritics) on their base-character followed by the diacritical mark
        // exmaple:  Ú => U´,  á => a`
        $s = $normalizer->normalize($s, $normalizer::FORM_D);

        $s = preg_replace('@\pM@u', "", $s);    // removes diacritics

        $s = preg_replace('@\x{00df}@u', "ss", $s);    // maps German ß onto ss
        $s = preg_replace('@\x{00c6}@u', "AE", $s);    // Æ => AE
        $s = preg_replace('@\x{00e6}@u', "ae", $s);    // æ => ae
        $s = preg_replace('@\x{0132}@u', "IJ", $s);    // ? => IJ
        $s = preg_replace('@\x{0133}@u', "ij", $s);    // ? => ij
        $s = preg_replace('@\x{0152}@u', "OE", $s);    // Œ => OE
        $s = preg_replace('@\x{0153}@u', "oe", $s);    // œ => oe

        $s = preg_replace('@\x{00d0}@u', "D", $s);    // Ð => D
        $s = preg_replace('@\x{0110}@u', "D", $s);    // Ð => D
        $s = preg_replace('@\x{00f0}@u', "d", $s);    // ð => d
        $s = preg_replace('@\x{0111}@u', "d", $s);    // d => d
        $s = preg_replace('@\x{0126}@u', "H", $s);    // H => H
        $s = preg_replace('@\x{0127}@u', "h", $s);    // h => h
        $s = preg_replace('@\x{0131}@u', "i", $s);    // i => i
        $s = preg_replace('@\x{0138}@u', "k", $s);    // ? => k
        $s = preg_replace('@\x{013f}@u', "L", $s);    // ? => L
        $s = preg_replace('@\x{0141}@u', "L", $s);    // L => L
        $s = preg_replace('@\x{0140}@u', "l", $s);    // ? => l
        $s = preg_replace('@\x{0142}@u', "l", $s);    // l => l
        $s = preg_replace('@\x{014a}@u', "N", $s);    // ? => N
        $s = preg_replace('@\x{0149}@u', "n", $s);    // ? => n
        $s = preg_replace('@\x{014b}@u', "n", $s);    // ? => n
        $s = preg_replace('@\x{00d8}@u', "O", $s);    // Ø => O
        $s = preg_replace('@\x{00f8}@u', "o", $s);    // ø => o
        $s = preg_replace('@\x{017f}@u', "s", $s);    // ? => s
        $s = preg_replace('@\x{00de}@u', "T", $s);    // Þ => T
        $s = preg_replace('@\x{0166}@u', "T", $s);    // T => T
        $s = preg_replace('@\x{00fe}@u', "t", $s);    // þ => t
        $s = preg_replace('@\x{0167}@u', "t", $s);    // t => t

        // remove all non-ASCii characters
        $s = preg_replace('@[^\0-\x80]@u', "", $s);

        // possible errors in UTF8-regular-expressions
        if (empty($s)) {
            // remove all non-whitelisted characters
            return  preg_replace('@[^a-zA-Z0-9._\-\s ]@u' , "", $original_string);
        }

        // return normalized string
        return $s;
    }
}
