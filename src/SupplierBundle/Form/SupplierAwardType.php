<?php

namespace SupplierBundle\Form;

use AppBundle\Entity\SupplierProfile;
use AppBundle\Form\AwardType;
use AppBundle\Form\SupplierProfileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplierAwardType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('awards', CollectionType::class, [
                'entry_type' => AwardType::class,
                'entry_options' => [
                    'label' => false
                ],
                'label' => 'Please add your award',
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'prototype' => true,
                'by_reference' => false,
                'attr' => [
                    'class' => 'award-collection'
                ]
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
        return 'supplierbundle_award';
    }


}
