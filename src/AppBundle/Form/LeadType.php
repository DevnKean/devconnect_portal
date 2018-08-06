<?php

namespace AppBundle\Form;

use AppBundle\Entity\Lead;
use AppBundle\Entity\Service;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LeadType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lead', EntityType::class, [
                'class' => Lead::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->orderBy('l.id', 'ASC');
                },
                'mapped' => false,
                'data' => $builder->getData()
            ])
//            ->add('function', ChoiceType::class, [
//                'choices' => array_combine(Service::getFunctions(), Service::getFunctions()),
//                'multiple' => true,
//                'placeholder' => 'Please select functions',
//                'attr' => [
//                    'class' => 'select2',
//                    'data-placeholder' => 'Please select functions'
//                ]
//            ])
//            ->add('lostReason', TextareaType::class, [
//                'label' => 'Internal Lost Reason notes',
//                'attr' =>  [
//                    'rows' => 5
//                ],
//                'required' => false
//            ])
            ->add('leadSuppliers', CollectionType::class, [
                'entry_type' => LeadSupplierType::class,
                'label' => false,
                'entry_options' => [
                    'label' => false
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'delete_empty' => true,
                'attr' => [
                    'class' => 'lead-collection'
                ]
            ]);

    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Lead'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lead';
    }


}
