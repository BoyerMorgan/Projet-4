<?php

namespace Louvre\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('mail', TextType::class, array('label' => 'Adresse email'))
            ->add('visitDate', DateType::class, array('label' => 'Date de visite souhaitée'))
            ->add('halfDay', CheckboxType::class, array(
                'required' => false,
                'label' => 'Billet demi-journée'
            ))
            ->add('tickets', CollectionType::class, array(
                'entry_type' => TicketsType::class,
                'allow_add' => true,
                'allow_delete' =>true,
                'label' => 'Billets'
            ))
            ->add('save', SubmitType::class, array('label' => 'Valider la commande'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\BackendBundle\Entity\Command'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'louvre_backendbundle_command_order';
    }


}
