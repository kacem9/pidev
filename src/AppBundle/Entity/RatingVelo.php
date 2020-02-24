<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RatingVeloController
 *
 * @ORM\Table(name="rating_velo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RatingVeloRepository")
 */
class RatingVelo
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
     * @var int
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;


    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Velo")
     * @ORM\JoinColumn(name="id_velo",referencedColumnName="id" )
     */
    private $idVelo;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="id_user",referencedColumnName="id")
     */
    private $idUser;

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
     * Set rating
     *
     * @param integer $rating
     *
     * @return RatingVelo
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set idVelo
     *
     * @param \AppBundle\Entity\Velo $idVelo
     *
     * @return RatingVelo
     */
    public function setIdVelo(\AppBundle\Entity\Velo $idVelo = null)
    {
        $this->idVelo = $idVelo;

        return $this;
    }

    /**
     * Get idVelo
     *
     * @return \AppBundle\Entity\Velo
     */
    public function getIdVelo()
    {
        return $this->idVelo;
    }

    /**
     * Set idUser
     *
     * @param \AppBundle\Entity\User $idUser
     *
     * @return RatingVelo
     */
    public function setIdUser(\AppBundle\Entity\User $idUser = null)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return \AppBundle\Entity\User
     */
    public function getIdUser()
    {
        return $this->idUser;
    }
}
