<?php

namespace EE\TYSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use EE\TYSBundle\Validator\Constraints\Files as Files;

/**
 * StoryCollection
 *
 * @ORM\Table(name="tys_story_collection")
 * @ORM\Entity(repositoryClass="EE\TYSBundle\Entity\StoryCollectionRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class StoryCollection
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
     * @Serializer\Expose
     * @Serializer\Groups({"get", "cget"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="organizationName", type="string", length=255)
     * @Serializer\Expose
     * @Serializer\Groups({"get", "cget"})
     */
    private $organizationName;

    /**
     * @var string
     *
     * @ORM\Column(name="tagline", type="string", length=255)
     * @Serializer\Expose
     * @Serializer\Groups({"cget"})
     */
    private $tagline;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Serializer\Expose
     * @Serializer\Groups({"get"})
     */
    private $description;

    /**
     * @Assert\File(
     *     maxSize = "32M",
     *     mimeTypes = {"image/png", "image/jpeg", "image/gif"},
     *     maxSizeMessage = "storyCollection.backgroundFilename.too.big.file",
     *     mimeTypesMessage = "storyCollection.backgroundFilename.mime.incorrect"
     * )
     */
    private $file;

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
     * @var Story[]
     *
     * @ORM\ManyToMany(targetEntity="EE\TYSBundle\Entity\Story", mappedBy="storyCollections")
     * @Serializer\Expose
     * @Serializer\Groups({"get"})
     */
    private $stories;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Serializer\Expose
     * @Serializer\Groups({"get"})
     */
    private $createdAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="EE\TYSBundle\Entity\User", inversedBy="storyCollections")
     */
    private $createdBy;

    /**
     *
     */
    public function __construct()
    {
        $this->stories = new ArrayCollection();
        $this->createdAt = new \DateTime();
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
     * @return $this
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
     * Set organizationName
     *
     * @param string $organizationName
     * @return $this
     */
    public function setOrganizationName($organizationName)
    {
        $this->organizationName = $organizationName;

        return $this;
    }

    /**
     * Get organizationName
     *
     * @return string
     */
    public function getOrganizationName()
    {
        return $this->organizationName;
    }

    /**
     * Set tagline
     *
     * @param string $tagline
     * @return $this
     */
    public function setTagline($tagline)
    {
        $this->tagline = $tagline;

        return $this;
    }

    /**
     * Get tagline
     *
     * @return string
     */
    public function getTagline()
    {
        return $this->tagline;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return $this
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
     * @param mixed $stories
     *
     * @return $this
     */
    public function setStories($stories)
    {
        $this->stories = $stories;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStories()
    {
        return $this->stories;
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
}
