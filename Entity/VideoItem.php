<?php

namespace EE\TYSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * VideoItem
 *
 * @ORM\Entity
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class VideoItem extends UrlItem
{
    /**
     * @var string
     *
     * @ORM\Column(name="embed_code", type="text")
     * @Serializer\Expose
     * @Serializer\Groups({"get"})
     */
    private $embedCode;

    /**
     * @param string $embedCode
     *
     * @return $this
     */
    public function setEmbedCode($embedCode)
    {
        $this->embedCode = $embedCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmbedCode()
    {
        return $this->embedCode;
    }

    /**
     * Returns unique type for item, e.g. 'url' or 'video'
     * In most cases it's the same as discriminator column value
     *
     * @return string
     */
    public function getType()
    {
        return 'video';
    }

}
