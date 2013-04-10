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
     * Return file size in bytes
     *
     * @return integer
     */
    public function getSize();

    /**
     * Set governing entity
     *
     * @var $parent Governing entity
     */
    public function setParent($parent);

    /**
     * Set uploaded file
     *
     * @var $file Uploaded file
     */
    public function setFile(\Symfony\Component\HttpFoundation\File\File $file);

    /**
     * Get file
     *
     * @return Symfony\Component\HttpFoundation\File\File
     */
    public function getFile();

    /**
     * Return true if file thumbnail should be generated
     *
     * @return boolean
     */
    public function getPreview();
}
