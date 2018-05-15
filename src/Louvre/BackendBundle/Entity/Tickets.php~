<?php

namespace Louvre\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tickets
 *
 * @ORM\Table(name="tickets")
 * @ORM\Entity(repositoryClass="Louvre\BackendBundle\Repository\TicketsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Tickets
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
     * @ORM\Column(name="name", type="string", length=25)
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage="Votre nom ne peut pas faire moins de {{ limit }} caractères.",
     *     maxMessage="Votre nom ne peut pas faire plus de {{ limite }} caractères"
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="forename", type="string", length=25)
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage="Votre nom ne peut pas faire moins de {{ limit }} caractères.",
     *     maxMessage="Votre nom ne peut pas faire plus de {{ limite }} caractères"
     * )
     */
    private $forename;

    /**
     * @var string
     *
     * @ORM\Column(name="birthDate", type="string")
     * @Assert\LessThan(
     *     "today",
     *     message = "Merci de vérifier votre date de naissance"
     * )
     */
    private $birthDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="reduced", type="boolean")
     */
    private $reduced;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\Country
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity="Command", inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $order;

    public function __construct(Command $order = null)
    {
        $this->order = $order;
    }


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
     * Set name
     *
     * @param string $name
     *
     * @return Tickets
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return Tickets
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set reduced
     *
     * @param boolean $reduced
     *
     * @return Tickets
     */
    public function setReduced($reduced)
    {
        $this->reduced = $reduced;

        return $this;
    }

    /**
     * Get reduced
     *
     * @return bool
     */
    public function getReduced()
    {
        return $this->reduced;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Tickets
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }


    /**
     * Set order
     *
     * @param \Louvre\BackendBundle\Entity\Command $order
     *
     * @return Tickets
     */
    public function setOrder(\Louvre\BackendBundle\Entity\Command $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \Louvre\BackendBundle\Entity\Command
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set forename
     *
     * @param string $forename
     *
     * @return Tickets
     */
    public function setForename($forename)
    {
        $this->forename = $forename;

        return $this;
    }

    /**
     * Get forename
     *
     * @return string
     */
    public function getForename()
    {
        return $this->forename;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Tickets
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }
}
