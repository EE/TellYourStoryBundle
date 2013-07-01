<?php

namespace EE\TYSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * TextItem
 *
 * @ORM\Entity
 */
class TextItem extends Item
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
