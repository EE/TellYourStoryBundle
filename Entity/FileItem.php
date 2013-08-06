<?php

namespace EE\TYSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use EE\TYSBundle\Validator\Constraints\Files as Files;

/**
 * FileItem
 *
 * @ORM\Entity
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class FileItem extends Item
{
    /**
     * @var array
     *
     * @ORM\Column(name="files", type="json_array")
     */
    private $files;

    /**
     * Dummy property used to generate form based on entity
     *
     * @var array
     *
     */
    private $uploadedFiles;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string")
     */
    private $description;

    /**
     * FileItem Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->files = array();
    }

    /**
     * @param array $files
     *
     * @return $this
     */
    public function setFiles($files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param string $file
     *
     * @return $this
     */
    public function addFile($file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * @param string $file
     *
     * @return $this
     */
    public function removeFile($file)
    {
        $this->files->removeElement($file);

        return $this;
    }

    /**
     * @param array $uploadedFiles
     *
     * @return $this
     */
    public function setUploadedFiles($uploadedFiles)
    {
        $this->uploadedFiles = $uploadedFiles;

        return $this;
    }

    /**
     * @return array
     */
    public function getUploadedFiles()
    {
        return $this->uploadedFiles;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns unique type for item, e.g. 'url' or 'video'
     * In most cases it's the same as discriminator column value
     *
     * @return string
     */
    public function getType()
    {
        return 'file';
    }
}
