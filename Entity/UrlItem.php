<?php

namespace EE\TYSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * UrlItem
 *
 * @ORM\Entity
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class UrlItem extends Item
{
    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string")
     * @Serializer\Expose
     * @Serializer\Groups({"get"})
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string")
     * @Serializer\Expose
     * @Serializer\Groups({"get"})
     */
    private $description;

    /**
     * @param string $url
     *
     * @return UrlItem
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
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
        return 'url';
    }
}
