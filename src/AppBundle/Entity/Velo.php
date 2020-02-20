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
     * @ORM\Column(name="prix", type="float",nullable=true)
     */
    private $prix;
    /**
     * @var float
     *
     * @ORM\Column(name="prix_location", type="float",nullable=true)
     */
    private $prix_location;

    /**
     * @var int
     *
     * @ORM\Column(name="nbruser", type="integer",nullable=true)
     */
    private $nbruser;

    /**
     * @var int
     *
     * @ORM\Column(name="etat_vendu", type="integer")
     */
    private $etatVendu;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;
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
     * @var int
     *
     * @ORM\Column(name="rating", type="integer", nullable=true)
     */
    private $rating;


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
    public function __toString()
    {
        return (string) $this->quantite;
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return Velo
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer
     */
    public function getQuantite()
    {
        return $this->quantite;
    }


    /**
     * Set rating
     *
     * @param integer $rating
     *
     * @return Velo
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set prixLocation
     *
     * @param float $prixLocation
     *
     * @return Velo
     */
    public function setPrixLocation($prixLocation)
    {
        $this->prix_location = $prixLocation;

        return $this;
    }

    /**
     * Get prixLocation
     *
     * @return float
     */
    public function getPrixLocation()
    {
        return $this->prix_location;
    }

    /**
     * Set nbruser
     *
     * @param integer $nbruser
     *
     * @return Velo
     */
    public function setNbruser($nbruser)
    {
        $this->nbruser = $nbruser;

        return $this;
    }

    /**
     * Get nbruser
     *
     * @return integer
     */
    public function getNbruser()
    {
        return $this->nbruser;
    }
}
