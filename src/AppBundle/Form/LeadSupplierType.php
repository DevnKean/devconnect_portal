<?php

namespace AppBundle\Form;

use AppBundle\Entity\Contract;
use AppBundle\Entity\LeadSupplier;
use AppBundle\Entity\Supplier;
use AppBundle\Entity\SupplierProfile;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LeadSupplierType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('supplier', EntityType::class, [
                'class' => Supplier::class,
                'choice_label' => 'businessName',
                'query_builder' => function(EntityRepository $er) {
                    $qb = $er->createQueryBuilder('s');
                    $qb->select('DISTINCT(s.id)')
                        ->innerJoin('s.supplierProfiles', 'f')
                        ->where($qb->expr()->in('f.status', [
                            SupplierProfile::STATUS_FEEDBACK,
                            SupplierProfile::STATUS_INCOMPLETE,
                            SupplierProfile::STATUS_PENDING
                        ]));
                    $results = $qb->getQuery()->getArrayResult();
                    $ids = [];
                    foreach ($results as $result) {
                        $ids[] = current($result);
                    }

                    $qb = $er->createQueryBuilder('s');
                    $qb->innerJoin('s.contracts', 'c')
                       ->where('c.status = :status')
                       ->setParameter('status', Contract::STATUS_APPROVED);
                    if (!empty($ids)) {
                        $qb->andWhere($qb->expr()->notIn('s.id', ':ids'))
                           ->setParameter('ids', $ids);
                    }

                    return $qb->orderBy('s.businessName', 'ASC');
                },
                'placeholder' => 'Please select a supplier to allocate...',

            ])
            ->add('leadStatus', TextType::class, [
                'label' => 'Supplier Lead Status',
                'empty_data' => 'Need to action',
                'attr' => [
                    'readonly' => true
                ]
            ])
            ->add('allocatedDate', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('internalNotes', TextareaType::class, [
                'attr' =>  [
                    'rows' => 5,
                    'class' => 'resize-vertically'
                ],
                'required' => false
            ])
            ->add('notesToOutsourcer', TextareaType::class, [
                'label' => 'Display note to Supplier',
                'attr' => [
                    'rows' => 5,
                    'class' => 'resize-vertically'
                ],
                'required' => false
            ])
            ->add('result', ChoiceType::class, [
                'choices' => array_combine(LeadSupplier::getAllocationResults(), LeadSupplier::getAllocationResults()),
                'placeholder' => 'Please select allocation result if applicable',
                'required' => false,
                'attr' => [
                    'class' => 'lead-result'
                ]
            ])
            ->add('lostReason', ChoiceType::class, [
                'choices' => array_combine(LeadSupplier::getLostReasons(), LeadSupplier::getLostReasons()),
                'placeholder' => 'Please select lost reason if applicable',
                'required' => false,
                'attr' => [
                    'class' => 'lost-reason'
                ]
            ])
            ->add('lostReasonNotes', TextareaType::class, [
                'label' => 'Lost reason note to Supplier',
                'attr' => [
                    'rows' => 5,
                    'class' => 'resize-vertically lost-reason-notes'
                ],
                'required' => false
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\LeadSupplier'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'LeadSupplierType';
    }


}
