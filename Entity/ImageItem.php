<?php

namespace EE\TYSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use EE\TYSBundle\Validator\Constraints\Files as Files;
use JMS\Serializer\Annotation as Serializer;

/**
 * ImageItem
 *
 * @ORM\Entity
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class ImageItem extends FileItem
{
    /**
     * Dummy property used to generate form based on entity
     *
     * @Files(
     *     maxSize = "32M",
     *     maxTotalSize = "32M",
     *     mimeTypes ={"image/jpeg", "image/gif", "image/png"}
     * )
     * @var array
     */
    private $uploadedFiles;

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
     * Returns unique type for item, e.g. 'url' or 'video'
     * In most cases it's the same as discriminator column value
     *
     * @return string
     */
    public function getType()
    {
        return 'image';
    }

}
