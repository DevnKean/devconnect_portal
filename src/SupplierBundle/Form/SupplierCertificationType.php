<?php

namespace SupplierBundle\Form;

use AppBundle\Entity\Supplier;
use AppBundle\Form\CertificationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplierCertificationType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Supplier $supplier */
        $supplier = $builder->getData();
        $builder
            ->add('professionalIndemnity', MoneyType::class, [
                'grouping' => true,
                'label' => 'Professional Indemnity',
                'currency' => 'AUD',
                'attr' => [
                    'placeholder' => 'Please enter the amount of Professional Indemnity you are insured for',
                    'class' => 'input-professionalIndemnity',
                ],
            ])
            ->add('publicLiability', MoneyType::class, [
                'grouping' => true,
                'label' => 'Public Liability',
                'currency' => 'AUD',
                'attr' => [
                    'placeholder' => 'Please enter the amount of Public Liability you are insured for',
                    'class' => 'input-publicLiability',
                ],
            ])
            ->add('certifications', CollectionType::class, [
                'entry_type' => CertificationType::class,
                'label' => $supplier->isOutSourcing() ? 'Please provide information about any professional certifications your business has that may be appealing to potential customers' : 'Please provide information about any professional certifications you have that may be appealing to potential customers',
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'delete_empty' => true,
                'attr' => [
                    'class' => 'certification-collection',
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Supplier',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'supplierbundle_certification';
    }


}
