<?php

namespace EE\TYSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use EE\LightUserBundle\Entity\User as BaseUser;

/**
 * @ORM\Entity()
 * @ORM\Table(name="tys_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
    protected $facebookId;

    /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
    protected $facebookAccessToken;

    /** @ORM\Column(name="facebook_real_name", type="string", length=255) */
    protected $facebookRealName;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EE\TYSBundle\Entity\Item", mappedBy="user")
     */
    protected $items;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EE\TYSBundle\Entity\Story", mappedBy="user")
     */
    protected $stories;

    /**
     * @var string
     *
     * @ORM\Column(name="organization", type="string", length=255, nullable=true)
     */
    protected $organization;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $active;


    /**
     * @param mixed $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $facebookAccessToken
     *
     * @return $this
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }

    /**
     * @param mixed $facebookId
     *
     * @return $this
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setFacebookRealName($name)
    {
        $this->facebookRealName = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookRealName()
    {
        return $this->facebookRealName;
    }

    /**
     * Proxy for view
     *
     * @return string
     */
    public function getRealName()
    {
        return $this->facebookRealName;
    }

    /**
     * Sets Item collection
     *
     * @param Collection $items
     *
     * @return $this
     */
    public function setItems(Collection $items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Sets Story collection
     *
     * @param Collection $stories
     *
     * @return $this
     */
    public function setStories(Collection $stories)
    {
        $this->stories = $stories;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getStories()
    {
        return $this->stories;
    }


    /**
     * @param string $organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }

    /**
     * @return string
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }
}