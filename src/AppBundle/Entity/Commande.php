<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandeRepository")
 */
class Commande
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateCommande", type="date")
     */
    private $dateCommande;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateMax", type="date")
     */
    private $dateMax;
    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="id_user",referencedColumnName="id")
     */
    private $idUser;
    /**
     * @var int
     *
     * @ORM\Column(name="etat_vendu", type="integer",nullable=true)
     */
    private $etat_vendu;
    /**
     * @var int
     *
     * @ORM\Column(name="etatLocation", type="integer",nullable=true)
     */
    private $etatLocation;


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
     * Set dateCommande
     *
     * @param \DateTime $dateCommande
     *
     * @return Commande
     */
    public function setDateCommande($dateCommande)
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    /**
     * Get dateCommande
     *
     * @return \DateTime
     */
    public function getDateCommande()
    {
        return $this->dateCommande;
    }

    /**
     * Set dateMax
     *
     * @param \DateTime $dateMax
     *
     * @return Commande
     */
    public function setDateMax($dateMax)
    {
        $this->dateMax = $dateMax;

        return $this;
    }

    /**
     * Get dateMax
     *
     * @return \DateTime
     */
    public function getDateMax()
    {
        return $this->dateMax;
    }

    /**
     * Set idUser
     *
     * @param \AppBundle\Entity\User $idUser
     *
     * @return Commande
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

    public function __toString()
    {
       return (string) $this->id;
    }

    /**
     * Set etatVendu
     *
     * @param integer $etatVendu
     *
     * @return Commande
     */
    public function setEtatVendu($etatVendu)
    {
        $this->etat_vendu = $etatVendu;

        return $this;
    }

    /**
     * Get etatVendu
     *
     * @return integer
     */
    public function getEtatVendu()
    {
        return $this->etat_vendu;
    }

    /**
     * Set etatLocation
     *
     * @param integer $etatLocation
     *
     * @return Commande
     */
    public function setEtatLocation($etatLocation)
    {
        $this->etatLocation = $etatLocation;

        return $this;
    }

    /**
     * Get etatLocation
     *
     * @return integer
     */
    public function getEtatLocation()
    {
        return $this->etatLocation;
    }
}
