<?php

namespace AppBundle\Form;

use AppBundle\Entity\MinimumVolume;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class MinimumVolumeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('inboundContacts', ChoiceType::class, [
                'choices' =>  array_combine(MinimumVolume::getInboundContactsOptions(), MinimumVolume::getInboundContactsOptions()),
                'label' => 'Inbound contacts per day',
                'expanded' => true,
                'multiple' => true,
                'required' => true
            ])
            ->add('headcount', ChoiceType::class, [
                'choices' => array_combine(MinimumVolume::getHeadcountOptions(), MinimumVolume::getHeadcountOptions()),
                'label' => 'Headcount',
                'expanded' => true,
                'multiple' => true,
                'required' => true
            ])
            ->add('campaignData', ChoiceType::class, [
                'choices' => array_combine(MinimumVolume::getCampaignDataOptions(), MinimumVolume::getCampaignDataOptions()),
                'label' => 'Data',
                'expanded' => true,
                'multiple' => true,
                'required' => true,
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\MinimumVolume',
            'constraints' => [
                new Callback(function (MinimumVolume $minimumVolume, ExecutionContextInterface $context, $payload) {
                    if (empty($minimumVolume->getInboundContacts())) {
                        $context->buildViolation('You must select at least one option in inbound contacts column')
                                ->atPath('inboundContacts')->addViolation();
                    }

                    if (empty($minimumVolume->getCampaignData())) {
                        $context->buildViolation('You must select at least one option in campaign data column')
                                ->atPath('campaignData')->addViolation();
                    }

                    if (empty($minimumVolume->getHeadcount())) {
                        $context->buildViolation('You must select at least one option in headcount column')
                                ->atPath('headcount')->addViolation();
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
        return 'appbundle_volume';
    }


}
