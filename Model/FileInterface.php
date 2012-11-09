<?php

namespace Admingenerator\GeneratorBundle\Model;

/**
 * Interface for Upload widget files.
 * 
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
interface FileInterface
{
    /**
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param $entity Associated entity
     */
    public function __construct($file, $entity);
    
    /**
     * Return entity unique identifier
     * 
     * @return integer
     */
    public function getId();

    /**
     * Return file name (e.g. image001.png)
     * 
     * @return string
     */
    public function getName();

    /**
     * Return file extension (e.g. png)
     * 
     * @return string
     */
    public function getExtension();

    /**
     * Return file mimetype
     * 
     * @return string
     */
    public function getType();

    /**
     * Return file size in bytes
     * 
     * @return integer
     */
    public function getSize();

    /**
     * Return true if file thumbnail should be generated
     * 
     * @return boolean
     */
    public function getPreview();

    /**
     * Set Symfony\Component\HttpFoundation\File\File
     */
    public function setFile(\Symfony\Component\HttpFoundation\File\File $file);

    /**
     * Return instance of Symfony\Component\HttpFoundation\File\File
     * 
     * @return Symfony\Component\HttpFoundation\File\File
     */
    public function getFile();
}
