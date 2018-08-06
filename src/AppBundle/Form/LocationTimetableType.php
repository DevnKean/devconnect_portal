<?php

namespace AppBundle\Form;

use AppBundle\Entity\LocationTimetable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class LocationTimetableType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('openDay', HiddenType::class)
            ->add('openTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
            ])
            ->add('closeTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
            ])
            ->add('isOpenWholeDay', CheckboxType::class, [
                'label' => 'Open 24 hours',
                'required' => false
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\LocationTimetable',
            'constraints' => [
                new Callback(function (LocationTimetable $locationTimetable, ExecutionContextInterface $context, $payload) {
                    if (!$locationTimetable->getIsOpenWholeDay()) {
                        if (empty($locationTimetable->getOpenTime())) {
                            $context->buildViolation('Open Time is required')
                            ->atPath('openTime')->addViolation();
                        }

                        if (empty($locationTimetable->getCloseTime())) {
                            $context->buildViolation('Close Time is required')
                                    ->atPath('closeTime')->addViolation();
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
        return 'appbundle_businesstimetable';
    }


}
