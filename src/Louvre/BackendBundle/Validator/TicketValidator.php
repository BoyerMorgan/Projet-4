<?php

namespace Louvre\BackendBundle\Validator;


use Doctrine\ORM\EntityManagerInterface;
use Louvre\BackendBundle\Entity\Command;
use Louvre\BackendBundle\Repository\CommandRepository;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class TicketValidator extends ConstraintValidator
{
    /**
     * @var CommandRepository
     */
    private $commandRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->commandRepository = $entityManager->getRepository('LouvreBackendBundle:Command');
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param Command $order The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($order, Constraint $constraint)
    {
       if(!$order instanceof  Command){
            return;
        }

        $nbTicketsSold = $this->commandRepository
            ->countTickets($order->getVisitDate())
        ;


        if ((1000 - $nbTicketsSold ) < 0) {
            $this->context->buildViolation($constraint->message)
                ->atPath('visitDate')
                ->addViolation();
        }
    }

}