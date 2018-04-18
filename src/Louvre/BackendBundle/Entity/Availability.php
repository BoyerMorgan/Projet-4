<?php

namespace Louvre\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Availability
 *
 * @ORM\Table(name="availability")
 * @ORM\Entity(repositoryClass="Louvre\BackendBundle\Repository\AvailabilityRepository")
 */
class Availability
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
     * @ORM\Column(name="date", type="datetime", unique=true)
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="ticketsSold", type="integer")
     */
    private $ticketsSold;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Availability
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set ticketsSold
     *
     * @param integer $ticketsSold
     *
     * @return Availability
     */
    public function setTicketsSold($ticketsSold)
    {
        $this->ticketsSold = $ticketsSold;

        return $this;
    }

    /**
     * Get ticketsSold
     *
     * @return int
     */
    public function getTicketsSold()
    {
        return $this->ticketsSold;
    }
}

