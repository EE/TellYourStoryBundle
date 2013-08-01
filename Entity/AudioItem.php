<?php

namespace EE\TYSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use EE\TYSBundle\Validator\Constrains\Files as Files;
use JMS\Serializer\Annotation as Serializer;

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
     *     maxSize = "10M",
     *     maxTotalSize = "10M",
     *     mimeTypes = {"audio/mpeg"},
     *     maxSizeMessage = "item.audio.uploadedFiles.too.big.file",
     *     mimeTypesMessage = "item.audio.uploadedFiles.mime.incorrect"
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
        return 'audio';
    }

}
