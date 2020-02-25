<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * rendezvous
 *
 * @ORM\Table(name="rendezvous")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\rendezvousRepository")
 */
class rendezvous
{
    /**
     * @var string
     *
     * @ORM\Column(name="Cin", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    private $Cin;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="typepanne", type="string", length=255)
     */
    private $typepanne;

    /**
     * @var int
     *
     * @ORM\Column(name="numtel", type="integer")
     */
    private $numtel;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user",referencedColumnName="id", onDelete="cascade")
     */
    private $user;

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

    /**
     * @return string
     */
    public function getCin()
    {
        return $this->Cin;
    }

    /**
     * @param string $Cin
     */
    public function setCin($Cin)
    {
        $this->Cin = $Cin;
    }




    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return rendezvous
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return rendezvous
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return rendezvous
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return rendezvous
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return rendezvous
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set typepanne
     *
     * @param string $typepanne
     *
     * @return rendezvous
     */
    public function setTypepanne($typepanne)
    {
        $this->typepanne = $typepanne;

        return $this;
    }

    /**
     * Get typepanne
     *
     * @return string
     */
    public function getTypepanne()
    {
        return $this->typepanne;
    }

    /**
     * Set numtel
     *
     * @param integer $numtel
     *
     * @return rendezvous
     */
    public function setNumtel($numtel)
    {
        $this->numtel = $numtel;

        return $this;
    }

    /**
     * Get numtel
     *
     * @return int
     */
    public function getNumtel()
    {
        return $this->numtel;
    }
}

