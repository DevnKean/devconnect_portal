<?php
/**
 * Created by PhpStorm.
 * User: Lixing
 * Date: 9/6/18
 * Time: 2:14 PM
 */

namespace SupplierBundle\Form;

use AppBundle\Form\DataAcquisitionProviderType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplierDataAcquisitionProviderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('dataAcquisitionProviders', CollectionType::class, [
            'entry_type' => DataAcquisitionProviderType::class,
            'entry_options' => [
                'label' => false
            ],
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'prototype' => true,
            'by_reference' => false,
            'attr' => [
                'class' => 'provider-collection'
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
        return 'supplierbundle_dataaquisitionprovider';
    }
}