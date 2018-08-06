<?php

namespace SupplierBundle\Form;

use AppBundle\Form\ContactType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplierContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contacts', CollectionType::class, [
                'entry_type' => ContactType::class,
                'entry_options' => [
                    'label' => false
                ],
//                'allow_add' => true,
//                'allow_delete' => true,
//                'prototype' => true,
//                'by_reference' => false,
//                'attr' => [
//                    'class' => 'contact-collection'
//                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Supplier'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'supplierbundle_contact';
    }


}
