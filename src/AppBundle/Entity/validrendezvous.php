<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * validrendezvous
 *
 * @ORM\Table(name="validrendezvous")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\validrendezvousRepository")
 */
class validrendezvous
{
    /**
     * @var integer
     *
     * @ORM\Column(name="reference", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    private $reference;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateheure", type="datetime")
     */
    private $dateheure;
    /**
     * @var string
     *  @Assert\Length(min = 8, max = 8, minMessage = "min_lenght", maxMessage = "max_lenght")
     * @Assert\Regex(pattern="/^[0-9]*$/", message="number_only")
     * @ORM\Column(name="prix", type="string", length=255)
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="promo", type="string", length=255)
     */
    private $promo;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255)
     */
    private $etat;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255)
     */
    private $message;



    /**
     *  @ORM\Column(name="emailR", type="string", length=255)
     */
    private $emailR;

    /**
     * @return mixed
     */
    public function getEmailR()
    {
        return $this->emailR;
    }

    /**
     * @param mixed $emailR
     */
    public function setEmailR($emailR)
    {
        $this->emailR = $emailR;
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


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user",referencedColumnName="id", onDelete="cascade")
     */
    private $user;

    /**
     * @return mixed
     */
    public function getRendezvous()
    {
        return $this->rendezvous;
    }

    /**
     * @param mixed $rendezvous
     */
    public function setRendezvous($rendezvous)
    {
        $this->rendezvous = $rendezvous;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }



    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }



    /**
     * Set dateheure
     *
     * @param \DateTime $dateheure
     *
     * @return validrendezvous
     */
    public function setDateheure($dateheure)
    {
        $this->dateheure = $dateheure;

        return $this;
    }

    /**
     * Get dateheure
     *
     * @return \DateTime
     */
    public function getDateheure()
    {
        return $this->dateheure;
    }

    /**
     * Set prix
     *
     * @param integer $prix
     *
     * @return validrendezvous
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return int
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set promo
     *
     * @param string $promo
     *
     * @return validrendezvous
     */
    public function setPromo($promo)
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * Get promo
     *
     * @return string
     */
    public function getPromo()
    {
        return $this->promo;
    }

    /**
     * Set etat
     *
     * @param string $etat
     *
     * @return validrendezvous
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }
}

