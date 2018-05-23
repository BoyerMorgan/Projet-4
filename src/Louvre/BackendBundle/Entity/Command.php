<?php

namespace Louvre\BackendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Louvre\BackendBundle\Validator as MyAssert;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * Command
 *
 * @ORM\Table(name="command")
 * @ORM\Entity(repositoryClass="Louvre\BackendBundle\Repository\CommandRepository")
 * @ORM\HasLifecycleCallbacks()
 * @MyAssert\Ticket()
 * @MyAssert\Hour()
 *
 */
class Command
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
     * @ORM\Column(name="mail", type="string", length=255)
     * @Assert\Email(
     *     message = "Veuillez entrer une adresse mail valide.",
     *     checkMX= true
     * )
     */
    private $mail;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="commandDate", type="datetime")
     */
    private $commandDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visitDate", type="datetime")
     * @Assert\GreaterThanOrEqual(
     *     "today",
     *     message = "Il n'est pas possible de réserver un billet pour une date antérieure à celle du jour."
     * )
     * @MyAssert\CheckDaysHolidays()
     * @MyAssert\CheckSunday()
     * @MyAssert\CheckTuesday()
     */
    private $visitDate;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var bool
     *
     * @ORM\Column(name="halfDay", type="boolean")
     */
    private $halfDay;

    /**
     * @var int
     *
     * @ORM\Column(name="nbTicket", type="integer")
     */
    private $nbTickets;

    /**
     * @var Tickets[]|ArrayCollection $tickets
     * @ORM\OneToMany(targetEntity="Louvre\BackendBundle\Entity\Tickets", mappedBy="order", cascade={"persist"})
     * @Assert\Valid()
     */
    private $tickets;

    /**
     * @var string
     *
     * @ORM\Column(name="orderId", type="string")
     */
    private $orderId;

    /**
     * @var float
     *
     * @ORM\Column(name="statut", type="string")
     */
    private $orderStatut;

    public function __construct()
    {
        $this->commandDate = new \Datetime();
        $this->visitDate = new \Datetime();
        $this->tickets = new ArrayCollection();
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
     * Set mail
     *
     * @param string $mail
     *
     * @return Command
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set commandDate
     *
     * @param \DateTime $commandDate
     *
     * @return Command
     */
    public function setCommandDate($commandDate)
    {
        $this->commandDate = $commandDate;

        return $this;
    }

    /**
     * Get commandDate
     *
     * @return \DateTime
     */
    public function getCommandDate()
    {
        return $this->commandDate;
    }

    /**
     * Set visitDate
     *
     * @param \DateTime $visitDate
     *
     * @return Command
     */
    public function setVisitDate($visitDate)
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    /**
     * Get visitDate
     *
     * @return \DateTime
     */
    public function getVisitDate()
    {
        return $this->visitDate;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Command
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set halfDay
     *
     * @param boolean $halfDay
     *
     * @return Command
     */
    public function setHalfDay($halfDay)
    {
        $this->halfDay = $halfDay;

        return $this;
    }

    /**
     * Get halfDay
     *
     * @return bool
     */
    public function getHalfDay()
    {
        return $this->halfDay;
    }

    /**
     * Add ticket
     *
     * @param Tickets $ticket
     *
     * @return Command
     */
    public function addTicket(Tickets $ticket)
    {
        $this->tickets[] = $ticket;
        $ticket->setOrder($this);

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param Tickets $ticket
     */
    public function removeTicket(Tickets $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }


    /**
     * Set nbTickets
     *
     * @param integer $nbTickets
     *
     * @return Command
     */
    public function setNbTickets($nbTickets)
    {
        $this->nbTickets = $nbTickets;

        return $this;
    }

    /**
     * Get nbTickets
     *
     * @return integer
     */
    public function getNbTickets()
    {
        return $this->nbTickets;
    }

    /**
     * Set orderId
     *
     * @param \mixed $orderId
     *
     * @return Command
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get orderId
     *
     * @return \mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set orderStatut
     *
     * @param string $orderStatut
     *
     * @return Command
     */
    public function setOrderStatut($orderStatut)
    {
        $this->orderStatut = $orderStatut;

        return $this;
    }

    /**
     * Get orderStatut
     *
     * @return string
     */
    public function getOrderStatut()
    {
        return $this->orderStatut;
    }
}
