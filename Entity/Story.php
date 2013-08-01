<?php

namespace EE\TYSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use EE\TYSBundle\Validator\Constrains\Files as Files;

/**
 * Story
 *
 * @ORM\Table(name="tys_story")
 * @ORM\Entity(repositoryClass="EE\TYSBundle\Entity\StoryRepository")
 * @Serializer\ExclusionPolicy("all")
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class Story
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose
     * @Serializer\Groups({"get", "cget"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     * @Serializer\Expose
     * @Serializer\Groups({"get", "cget"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank()
     * @Serializer\Expose
     * @Serializer\Groups({"get"})
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="address", type="string", length=255)
     * @Assert\NotBlank()
     * @Serializer\Expose
     * @Serializer\Groups({"get"})
     */
    private $address;

    /**
     * @var string|null
     *
     * @ORM\Column(name="background_filename", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({"get", "cget"})
     * @Serializer\Accessor(getter="getBackgroundFilenameForSerialization")
     * @Serializer\Type("Filename")
     * @Serializer\SerializedName("background_uri")
     */
    private $backgroundFilename;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Serializer\Expose
     * @Serializer\Groups({"get"})
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="tagline", type="string", length=255)
     * @Serializer\Expose
     * @Serializer\Groups({"cget"})
     */
    private $tagline;

    /**
     * @Assert\File(
     *     maxSize = "32M",
     *     mimeTypes = {"image/png", "image/jpeg", "image/gif"},
     *     maxSizeMessage = "story.backgroundFilename.too.big.file",
     *     mimeTypesMessage = "story.backgroundFilename.mime.incorrect"
     * )
     */
    private $file;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="EE\TYSBundle\Entity\Item", mappedBy="story")
     */
    private $items;

    /**
     * @var User
     * 
     * @ORM\ManyToOne(targetEntity="EE\TYSBundle\Entity\User", inversedBy="stories")
     *
     */
    private $createdBy;

    /**
     * Is it enabled to add new items by other users?
     *
     * @var boolean
     *
     * @ORM\Column(name="is_coeditable", type="boolean")
     */
    private $coeditable;

    /**
     * Is it visible?
     *
     * @var boolean
     *
     * @ORM\Column(name="is_published", type="boolean")
     */
    private $published;





    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->coeditable = false;
        $this->published = false;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Story
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Story
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Story
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param null|string $backgroundFilename
     *
     * @return $this
     */
    public function setBackgroundFilename($backgroundFilename)
    {
        $this->backgroundFilename = $backgroundFilename;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getBackgroundFilename()
    {
        return $this->backgroundFilename;
    }

    /**
     * @return Filename
     */
    public function getBackgroundFilenameForSerialization()
    {
        if ($this->backgroundFilename) {
            return new Filename($this->backgroundFilename);
        } else {
            return null;
        }
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Story
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function serialize() {
        return array(
            'name' => $this->getName()
        );
    }

    /**
     * @param string $tagline
     * @return $this
     */
    public function setTagline($tagline)
    {
        $this->tagline = $tagline;

        return $this;
    }

    /**
     * @return string
     */
    public function getTagline()
    {
        return $this->tagline;
    }

    /**
     * @param mixed $items
     *
     * @return $this
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $file
     *
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }


    /**
     * @param boolean $coeditable
     */
    public function setCoeditable($coeditable)
    {
        $this->coeditable = $coeditable;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getCoeditable()
    {
        return $this->coeditable;
    }

    /**
     * @param \EE\TYSBundle\Entity\User $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return \EE\TYSBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param boolean $published
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

}
