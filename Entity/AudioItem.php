<?php

namespace EE\TYSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use EE\TYSBundle\Validator\Constrains\Files as Files;


/**
 * AudioItem
 *
 * @ORM\Entity
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class AudioItem extends FileItem
{
    /**
     * Dummy property used to generate form based on entity
     *
     * @Files(
     *     maxSize = "600k",
     *     maxTotalSize = "800k",
     *     mimeTypes = {"audio/mpeg"}
     * )
     * @var array
     */
    public $uploadedFiles;

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
