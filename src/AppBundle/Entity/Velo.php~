<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Velo
 *
 * @ORM\Table(name="velo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VeloRepository")
 */
class Velo
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
     * @ORM\Column(name="date_circulation", type="date")
     */
    private $dateCirculation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePublication", type="date")
     */
    private $datePublication;


    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     */
    private $prix;

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
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;


    /**
     * @var string
     *
     * @ORM\Column(name="localitsation_velo", type="string", length=255)
     */
    private $localitsation_velo;
    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255)
     */
    private $photo;
    /**
     * @var string
     *
     * @ORM\Column(name="age_recommande", type="string", length=255)
     */
    private $age_recommande;
    /**
 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="velo")
 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
 */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Categories", inversedBy="categorie")
     * @ORM\JoinColumn(name="categories_id", referencedColumnName="id")
     */
    private $categories;
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
     * Set dateCirculation
     *
     * @param \DateTime $dateCirculation
     *
     * @return Velo
     */
    public function setDateCirculation($dateCirculation)
    {
        $this->dateCirculation = $dateCirculation;

        return $this;
    }

    /**
     * Get dateCirculation
     *
     * @return \DateTime
     */
    public function getDateCirculation()
    {
        return $this->dateCirculation;
    }

    /**
     * Set datePublication
     *
     * @param \DateTime $datePublication
     *
     * @return Velo
     */
    public function setDatePublication($datePublication)
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    /**
     * Get datePublication
     *
     * @return \DateTime
     */
    public function getDatePublication()
    {
        return $this->datePublication;
    }

    /**
     * Set prix
     *
     * @param float $prix
     *
     * @return Velo
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

    /**
     * Set modele
     *
     * @param string $modele
     *
     * @return Velo
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
     * Set categories
     *
     * @param string $categories
     *
     * @return Velo
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get categories
     *
     * @return string
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Velo
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
     * Set localitsationVelo
     *
     * @param string $localitsationVelo
     *
     * @return Velo
     */
    public function setLocalitsationVelo($localitsationVelo)
    {
        $this->localitsation_velo = $localitsationVelo;

        return $this;
    }

    /**
     * Get localitsationVelo
     *
     * @return string
     */
    public function getLocalitsationVelo()
    {
        return $this->localitsation_velo;
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Velo
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
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
     * Set ageRecommande
     *
     * @param string $ageRecommande
     *
     * @return Velo
     */
    public function setAgeRecommande($ageRecommande)
    {
        $this->age_recommande = $ageRecommande;

        return $this;
    }

    /**
     * Get ageRecommande
     *
     * @return string
     */
    public function getAgeRecommande()
    {
        return $this->age_recommande;
    }
    public function _toString(){
        return $this->nom;
    }
}
