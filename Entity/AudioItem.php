<?php

namespace EE\TYSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * AudioItem
 *
 * @ORM\Entity
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class AudioItem extends FileItem
{
    /**
     * Returns unique type for item, e.g. 'url' or 'video'
     * In most cases it's the same as discriminator column value
     *
     * @return string
     */
    public function getType()
    {
        return 'audio';
    }

}
