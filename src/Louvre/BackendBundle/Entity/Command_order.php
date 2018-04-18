<?php

namespace Louvre\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Command_order
 *
 * @ORM\Table(name="command_order")
 * @ORM\Entity(repositoryClass="Louvre\BackendBundle\Repository\Command_orderRepository")
 */
class Command_order
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
     */
    private $mail;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="commandDate", type="datetimetz")
     */
    private $commandDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visitDate", type="datetimetz")
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
     * @ORM\Column(name="idTicket", type="integer")
     */
    private $idOrder;


    public function __construct()
    {
        $this->commandDate = new \Datetime();
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
     * @return Command_order
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
     * @return Command_order
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
     * @return Command_order
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
     * @return Command_order
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
     * @return Command_order
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
     * Set idTicket
     *
     * @param integer $idOrder
     *
     * @return Command_order
     */
    public function setIdOrder($idOrder)
    {
        $this->idOrder = $idOrder;

        return $this;
    }

    /**
     * Get idTicket
     *
     * @return int
     */
    public function getIdOrder()
    {
        return $this->idOrder;
    }
}

