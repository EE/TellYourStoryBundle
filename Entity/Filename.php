<?php

namespace EE\TYSBundle\Entity;

/**
 * Class Filename
 * @package EE\TYSBundle\Entity
 */
class Filename {

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $extension;

    public function __construct($filename)
    {
        $parts = explode(".", $filename);

        $this->setName($parts[0]);

        $this->setExtension($parts[1]);

    }

    /**
     * @param mixed $extension
     * @return Media
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param mixed $name
     * @return Filename
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string filename
     */
    public function __toString()
    {
        return join(".", array($this->name, $this->extension));
    }

}