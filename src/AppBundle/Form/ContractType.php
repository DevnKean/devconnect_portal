<?php

namespace AppBundle\Form;

use AppBundle\Entity\CommissionModel;
use AppBundle\Entity\Contract;
use AppBundle\Entity\Service;
use AppBundle\Entity\Supplier;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ContractType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
             ->add('supplier', EntityType::class, [
                 'class' => Supplier::class,
                 'query_builder' => function(EntityRepository $er) {
                     return $er->createQueryBuilder('s')
                               ->orderBy('s.businessName', 'ASC');
                 },
                 'placeholder' => 'Please select a supplier to allocate...',
             ])
            ->add('contractServices', CollectionType::class, [
                'entry_type' => ContractServiceType::class,
                'entry_options' => [
                    'label' => false
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'attr' => [
                    'class' => 'service-collection'
                ]
            ])
            ->add('status', ChoiceType::class, [
                    'choices' => array_combine(Contract::getStatuses(), Contract::getStatuses()),
                    'placeholder' => 'Please Select Status'
                ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Start Date (yyyy-mm-dd)',
                'html5' => false
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'End Date (yyyy-mm-dd)',
                'html5' => false
            ])
            ->add('sentAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Sent Date (yyyy-mm-dd)',
                'html5' => false
            ])
            ->add('receivedAt', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Received At (yyyy-mm-dd)',
                'html5' => false
            ])
            ->add('file', FileType::class, [
                'label' => 'Contract (PDF File)',
                'data_class' => null,
                'required' => is_null($builder->getData()->getId())
            ])
            ->add('paymentTerm', IntegerType::class, [
                'label' => 'Number of days we need to add on top of the due payment date'
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Contract',
            'constraints' => [
                new Callback(function (Contract $contract, ExecutionContextInterface $context, $payload) {
                    if ($contract->getStatus() === Contract::STATUS_APPROVED) {
                        if (empty($contract->getReceivedAt())) {
                            $context->buildViolation('Received date can not be empty if status is approved')
                                    ->atPath('receivedAt')->addViolation();
                        }

                        if (empty($contract->getStartDate())) {
                            $context->buildViolation('Start Date can not be empty if status if approved')
                                ->atPath('startDate')->addViolation();
                        }

                        if (empty($contract->getEndDate())) {
                            $context->buildViolation('End Date can not be empty if status is approved')
                                ->atPath('endDate')->addViolation();
                        }
                    }
                })
            ]
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_contract';
    }


}
