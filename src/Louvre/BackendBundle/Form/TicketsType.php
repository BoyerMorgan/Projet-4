<?php

namespace Louvre\BackendBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Nom'))
            ->add('forename', TextType::class, array('label' => 'Prénom'))
            ->add('birthDate', BirthdayType::class, array(
                'label' =>'Date de naissance',
                'format' => 'dd-MM-yyyy',
                'input' => 'string'
            ))
            ->add('reduced', CheckboxType::class, array(
                'required' => false,
                'label' => 'Tarif réduit'
            ))
            ->add('country', CountryType::class, array(
                'label' => 'Pays de résidence',
                'preferred_choices' => array(
                    'FR',
                )
            ));

    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\BackendBundle\Entity\Tickets'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'louvre_backendbundle_tickets_command';
    }


}
