<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Currency;

class CommissionTierType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tierLevel')
            ->add('lowerThreshold', MoneyType::class, [
                'currency' => 'AUD'
            ])
            ->add('upperThreshold', MoneyType::class, [
                'currency' => 'AUD'
            ])
            ->add('rateYearOne', PercentType::class, [
                'scale' => 2
            ])
            ->add('rateYearTwo', PercentType::class, [
                'scale' => 2
            ])
            ->add('rateYearThree', PercentType::class, [
                'scale' => 2
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\CommissionTier'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_commissiontier';
    }


}
