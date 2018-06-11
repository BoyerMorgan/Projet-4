<?php

namespace Louvre\BackendBundle\Form;


use Louvre\BackendBundle\Entity\Command;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BilletType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tickets', CollectionType::class, array(
            'entry_type' => TicketsType::class,
            'allow_add' => true,
            'allow_delete' =>true,
            'label' => 'Billets',
            'entry_options' => array(
                'label' => false
            )
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Command::class,
            'validation_groups' => ['step2']
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return parent::getBlockPrefix();
    }
}