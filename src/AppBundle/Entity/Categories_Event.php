<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categories_Event
 *
 * @ORM\Table(name="categories__event")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Categories_EventRepository")
 */
class Categories_Event
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Event", mappedBy="categories_Event",cascade={"remove"})
     */
    private $event;
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Categories_Event
     */
    public function setType($type)
    {
        $this->type = $type ;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

}

