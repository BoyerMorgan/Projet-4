<?php

namespace Louvre\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tickets_command
 *
 * @ORM\Table(name="tickets_command")
 * @ORM\Entity(repositoryClass="Louvre\BackendBundle\Repository\Tickets_commandRepository")
 */
class Tickets_command
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
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthDate", type="datetimetz")
     */
    private $birthDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="reduced", type="boolean")
     */
    private $reduced;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @var int
     *
     * @ORM\Column(name="idOrder", type="integer")
     */
    private $idOrder;


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
     * @return Tickets_command
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
     * @return Tickets_command
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
     * @return Tickets_command
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
     * @return Tickets_command
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
     * Set idOrder
     *
     * @param integer $idOrder
     *
     * @return Tickets_command
     */
    public function setIdOrder($idOrder)
    {
        $this->idOrder = $idOrder;

        return $this;
    }

    /**
     * Get idOrder
     *
     * @return int
     */
    public function getIdOrder()
    {
        return $this->idOrder;
    }
}

