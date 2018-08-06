<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PotentialSupplierType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prefix')
            ->add('firstName')
            ->add('lastName')
            ->add('jobTitle')
            ->add('contactNumber')
            ->add('email')
            ->add('businessName')
            ->add('address')
            ->add('abnNumber')
            ->add('website')
            ->add('username')
            ->add('initialPassword');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\PotentialSupplier'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_potentialsupplier';
    }


}
