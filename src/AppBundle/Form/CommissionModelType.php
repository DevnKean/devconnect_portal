<?php

namespace AppBundle\Form;

use AppBundle\Entity\CommissionModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CommissionModelType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('isFlatRate', CheckboxType::class, [
                'label' => 'Is Flat Rate?',
                'required' => false,
            ])
            ->add('file', FileType::class, [
                'label' => 'Commission Tiers (csv, xls)',
                'data_class' => null,
                'required' => false
            ])
            ->add('flatRate', PercentType::class, [
                'label' => 'Flat Rate',
                'required' => false,
                'scale' => 2
            ]);
//            ->add('hasHeader', CheckboxType::class, [
//                'label' => 'First line contains headers',
//                'data_class' => null,
//                'required' => false
//            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CommissionModelData::class,
            'constraints' => [
                new Callback(function (CommissionModelData $commissionModel, ExecutionContextInterface $context, $payload) {
                    if ($commissionModel->isFlatRate() && empty($commissionModel->getFlatRate())) {
                        $context->buildViolation('Flat Rate is required')
                            ->atPath('flatRate')->addViolation();
                    }
                    if (!$commissionModel->isFlatRate() && empty($commissionModel->getFile())) {
                        $context->buildViolation('File is required')
                                ->atPath('file')->addViolation();
                    }

                    if ($commissionModel->getFile() && $commissionModel->getFlatRate()) {
                        $context->buildViolation('File and flat rate can not be used at same time')
                                ->atPath('file')->addViolation();
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
        return 'appbundle_commissionmodel';
    }


}
