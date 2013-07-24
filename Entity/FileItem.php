<?php

namespace EE\TYSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FileItem
 *
 * @ORM\Entity
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class FileItem extends Item
{
    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string")
     */
    private $filename;

    /**
     * @var array
     *
     * @ORM\Column(name="files", type="json_array")
     */
    public $files;

    /**
     * Dummy property used to generate form based on entity
     *
     * @var array
     */
    public $uploadedFiles;

    /**
     * FileItem Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->files = new ArrayCollection();
    }

    /**
     * @param string $filename
     *
     * @return FileItem
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
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
