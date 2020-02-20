<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * produits
 *
 * @ORM\Table(name="produits")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\produitsRepository")
 */
class produits
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
     * @ORM\Column(name="nom", type="string", length=255)
     *  @Assert\Regex(
     *     pattern="/^[A-Za-z]+$/",
     *     message="Only letters allowed"
     * )
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="modele", type="string", length=255)
     */
    private $modele;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     * @Assert\Regex(
     *     pattern="/^[0-9]+$/",
     *     message="Only numbers allowed"
     * )
     */
    private $prix;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="produits")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255)
     */
    private $photo;

    /**
     * @var int
     *
     * @ORM\Column(name="etat_vendu", type="integer")
     */
    private $etatVendu;
    /**
     * @var int
     *
     * @ORM\Column(name="etat_location", type="integer")
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
     * Set nom
     *
     * @param string $nom
     *
     * @return produits
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set modele
     *
     * @param string $modele
     *
     * @return produits
     */
    public function setModele($modele)
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * Get modele
     *
     * @return string
     */
    public function getModele()
    {
        return $this->modele;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return produits
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set prix
     *
     * @param float $prix
     *
     * @return produits
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return produits
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;
    }
    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Velo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }
    /**
     * Set etatVendu
     *
     * @param integer $etatVendu
     *
     * @return Velo
     */
    public function setEtatVendu($etatVendu)
    {
        $this->etatVendu = $etatVendu;

        return $this;
    }

    /**
     * Get etatVendu
     *
     * @return int
     */
    public function getEtatVendu()
    {
        return $this->etatVendu;
    }

    /**
     * Set etatLocation
     *
     * @param integer $etatLocation
     *
     * @return Velo
     */
    public function setEtatLocation($etatLocation)
    {
        $this->etatLocation = $etatLocation;

        return $this;
    }

    /**
     * Get etatLocation
     *
     * @return int
     */
    public function getEtatLocation()
    {
        return $this->etatLocation;
    }

}