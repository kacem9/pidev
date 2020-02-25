<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Actualite
 *
 * @ORM\Table(name="actualite")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ActualiteRepository")
 */
class Actualite
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;
    /**
     * @var int
     *
     * @ORM\Column(name="nbrLike", type="integer")
     */
    private $nbrLike=0;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrFoisLike", type="integer")
     */
    private $nbrFoisLike=0;

    /**
     * @var float
     *
     * @ORM\Column(name="moyenneLike", type="float")
     */
    private $moyenneLike=0;

    /**
     * @return int
     */
    public function getNbrLike()
    {
        return $this->nbrLike;
    }

    /**
     * @param int $nbrLike
     */
    public function setNbrLike($nbrLike)
    {
        $this->nbrLike = $nbrLike;
    }

    /**
     * @return int
     */
    public function getNbrFoisLike()
    {
        return $this->nbrFoisLike;
    }

    /**
     * @param int $nbrFoisLike
     */
    public function setNbrFoisLike($nbrFoisLike)
    {
        $this->nbrFoisLike = $nbrFoisLike;
    }

    /**
     * @return float
     */
    public function getMoyenneLike()
    {
        return $this->moyenneLike;
    }

    /**
     * @param float $moyenneLike
     */
    public function setMoyenneLike($moyenneLike)
    {
        $this->moyenneLike = $moyenneLike;
    }


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_publication", type="date")
     */
    private $datePublication;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user",referencedColumnName="id", onDelete="cascade")
     */
    private $user;


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
     * Set titre
     *
     * @param string $titre
     *
     * @return Actualite
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Actualite
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Actualite
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
     * Set datePublication
     *
     * @param \DateTime $datePublication
     *
     * @return Actualite
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
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

}
