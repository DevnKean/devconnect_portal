<?php
/**
 * Created by PhpStorm.
 * User: Lixing
 * Date: 29/4/18
 * Time: 3:10 PM
 */

namespace SupplierBundle\Form;


use AppBundle\Entity\WorkFromHome;
use AppBundle\Form\WorkFromHomeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplierWorkFromHomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('workFromHomes', CollectionType::class, [
                'entry_type' => WorkFromHomeType::class,
                'entry_options' => [
                    'label' => false,
//                    'supplier' => $builder->getData()
                ],
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'attr' => [
                    'class' => 'workfromhome-collection'
                ],
                'empty_data' =>$builder->getData()->getWorkFromHomes()
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Supplier'
        ));
    }

    public function getBlockPrefix()
    {
        return 'supplierbundle_workformhome';
    }
}