<?php

namespace AppBundle\Form;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Location;
use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class LocationType extends AbstractType
{
    /**
     * @var User
     */
    private $user;

    public function __construct(TokenStorageInterface $storage)
    {
        $this->user = $storage->getToken()->getUser();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $supplier = $this->user->getSupplier();
        $builder
            ->add('address', ComplexGooglePlaceAutocompleteType::class, [
                'label' => 'Full Address (You must select a Google matched address)'
            ])
            ->add('yearsOpen', ChoiceType::class, [
                'choices' => array_combine(Customer::getYears(), Customer::getYears()),
                'placeholder' => $supplier->isOutSourcing() ? 'Please Select Years facility has been open...' : 'Number of years you’ve been operating from this address',
                'label' => $supplier->isOutSourcing() ? 'Please Select Years facility has been open...' : 'Number of years you’ve been operating from this address',
            ]);
        if ($supplier->isOutSourcing()) {
            $builder
                ->add('totalSeats', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'Please enter the total number of seats at this facility'
                ]
            ])
                ->add('availableSeats', IntegerType::class, [
                    'attr' => [
                        'placeholder' => 'Number of  seats currently available'
                    ]
                ]);
        }

        if ($supplier->isVirtualAssistant()) {
            $builder->add('operateFrom', ChoiceType::class, [
                'choices' => [
                    'Private House/Unit/Apartment' => 'Private House/Unit/Apartment',
                    'Shared house/unit/Apartment' => 'Shared Commercial space',
                    'Private Commercial space' => 'Private Commercial space',
                ],
                'label' => 'How would you describe the location where you operate from?',
            ]);
            $builder->add('conductIn', ChoiceType::class, [
                'choices' => [
                    'An open room with other people' => 'An open room with other people',
                    'a closed room with no other people' => 'a closed room with no other people',
                ],
                'label' => 'Is the calling conducted in?',
            ]);

            $builder->add('noiseCancelling', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'label' => 'Do you have noise cancelling headsets?'
            ]);
            }

           $builder
               ->add('isMondayClosed', CheckboxType::class, [
                   'label' => 'We/I don\'t work on Monday',
                   'required' => false
               ])
               ->add('mondayOpenTime', TimeType::class, [
               'input'  => 'datetime',
               'widget' => 'single_text',
               'html5' => false,
               'required' => false,
               'attr' => [
                   'class' => 'open-time'
               ]
           ])
            ->add('mondayCloseTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => [
                    'class' => 'close-time'
                ]
            ]);

            if($supplier->isOutSourcing()) {
                 $builder->add('isMondayOpen24Hours', CheckboxType::class, [
                     'label' => 'Open 24 hours',
                     'required' => false
                 ]);
             }

            $builder
                ->add('isTuesdayClosed', CheckboxType::class, [
                    'label' => 'We/I don\'t work on Tuesday ',
                    'required' => false
                ])
                ->add('tuesdayOpenTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => [
                    'class' => 'open-time'
                ]
            ])
            ->add('tuesdayCloseTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => [
                    'class' => 'close-time'
                ]
            ]);

            if ($supplier->isOutSourcing()) {
                $builder->add('isTuesdayOpen24Hours', CheckboxType::class, [
                    'label' => 'Open 24 hours',
                    'required' => false
                ]);
            }

            $builder
                ->add('isWednesdayClosed', CheckboxType::class, [
                    'label' => 'We/I don\'t work on Wednesday',
                    'required' => false
                ])
                ->add('wednesdayOpenTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => [
                    'class' => 'open-time'
                ]
            ])
            ->add('wednesdayCloseTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => [
                    'class' => 'close-time'
                ]
            ]);
            if ($supplier->isOutSourcing()) {
                $builder->add('isWednesdayOpen24Hours', CheckboxType::class, [
                    'label' => 'Open 24 hours',
                    'required' => false
                ]);
            }

            $builder
                ->add('isThursdayClosed', CheckboxType::class, [
                    'label' => 'We/I don\'t work on Thursday',
                    'required' => false
                ])
                ->add('thursdayOpenTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => [
                    'class' => 'open-time'
                ]
            ])
            ->add('thursdayCloseTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => [
                    'class' => 'close-time'
                ]
            ]);
            if ($supplier->isOutSourcing()) {
                $builder->add('isThursdayOpen24Hours', CheckboxType::class, [
                    'label' => 'Open 24 hours',
                    'required' => false
                ]);
            }

            $builder
                ->add('isFridayClosed', CheckboxType::class, [
                    'label' => 'We/I don\'t work on Friday',
                    'required' => false
                ])
                ->add('fridayOpenTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => [
                    'class' => 'open-time'
                ]
            ])
            ->add('fridayCloseTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => [
                    'class' => 'close-time'
                ]
            ]);
            if ($supplier->isOutSourcing()) {
                $builder->add('isFridayOpen24Hours', CheckboxType::class, [
                    'label' => 'Open 24 hours',
                    'required' => false
                ]);
            }

            $builder
                ->add('isSaturdayClosed', CheckboxType::class, [
                    'label' => 'We/I don\'t work on Saturday',
                    'required' => false
                ])
                ->add('saturdayOpenTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => [
                    'class' => 'open-time'
                ]
            ])
            ->add('saturdayCloseTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => [
                    'class' => 'close-time'
                ]
            ]);

            if ($supplier->isOutSourcing()) {
                $builder->add('isSaturdayOpen24Hours', CheckboxType::class, [
                    'label' => 'Open 24 hours',
                    'required' => false
                ]);
            }

            $builder
                ->add('isSundayClosed', CheckboxType::class, [
                    'label' => 'We/I don\'t work on Sunday',
                    'required' => false
                ])
                ->add('sundayOpenTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => [
                    'class' => 'open-time'
                ]
            ])
            ->add('sundayCloseTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => [
                    'class' => 'close-time'
                ]
            ]);
            if ($supplier->isOutSourcing()) {
                $builder->add('isSundayOpen24Hours', CheckboxType::class, [
                    'label' => 'Open 24 hours',
                    'required' => false
                ]);
            }

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Location',
            'constraints' => [
                new Callback(function (Location $location, ExecutionContextInterface $context, $payload) {
                    if (!$location->isMondayOpen24Hours() && !$location->isMondayClosed()) {
                        if (empty($location->getMondayOpenTime())) {
                            $context->buildViolation('Open Time is required')
                                    ->atPath('mondayOpenTime')->addViolation();
                        }

                        if (empty($location->getMondayCloseTime())) {
                            $context->buildViolation('Close Time is required')
                                    ->atPath('mondayCloseTime')->addViolation();
                        }
                    }

                    if (!$location->isTuesdayOpen24Hours() && !$location->isTuesdayClosed()) {
                        if (empty($location->getTuesdayOpenTime())) {
                            $context->buildViolation('Open Time is required')
                                    ->atPath('tuesdayOpenTime')->addViolation();
                        }

                        if (empty($location->getTuesdayCloseTime())) {
                            $context->buildViolation('Close Time is required')
                                    ->atPath('tuesdayCloseTime')->addViolation();
                        }
                    }

                    if (!$location->isWednesdayOpen24Hours() && !$location->isWednesdayClosed()) {
                        if (empty($location->getWednesdayOpenTime())) {
                            $context->buildViolation('Open Time is required')
                                    ->atPath('wednesdayOpenTime')->addViolation();
                        }

                        if (empty($location->getWednesdayCloseTime())) {
                            $context->buildViolation('Close Time is required')
                                    ->atPath('wednesdayCloseTime')->addViolation();
                        }
                    }

                    if (!$location->isThursdayOpen24Hours() && !$location->isThursdayClosed()) {
                        if (empty($location->getThursdayOpenTime())) {
                            $context->buildViolation('Open Time is required')
                                    ->atPath('thursdayOpenTime')->addViolation();
                        }

                        if (empty($location->getThursdayCloseTime())) {
                            $context->buildViolation('Close Time is required')
                                    ->atPath('thursdayCloseTime')->addViolation();
                        }
                    }

                    if (!$location->isFridayOpen24Hours() && !$location->isFridayClosed()) {
                        if (empty($location->getFridayOpenTime())) {
                            $context->buildViolation('Open Time is required')
                                    ->atPath('fridayOpenTime')->addViolation();
                        }

                        if (empty($location->getFridayCloseTime())) {
                            $context->buildViolation('Close Time is required')
                                    ->atPath('fridayCloseTime')->addViolation();
                        }
                    }

                    if (!$location->isSaturdayOpen24Hours() && !$location->isSaturdayClosed()) {
                        if (empty($location->getSaturdayOpenTime())) {
                            $context->buildViolation('Open Time is required')
                                    ->atPath('saturdayOpenTime')->addViolation();
                        }

                        if (empty($location->getSaturdayCloseTime())) {
                            $context->buildViolation('Close Time is required')
                                    ->atPath('saturdayCloseTime')->addViolation();
                        }
                    }

                    if (!$location->isSundayOpen24Hours() && !$location->isSundayClosed()) {
                        if (empty($location->getSundayOpenTime())) {
                            $context->buildViolation('Open Time is required')
                                    ->atPath('sundayOpenTime')->addViolation();
                        }

                        if (empty($location->getSundayCloseTime())) {
                            $context->buildViolation('Close Time is required')
                                    ->atPath('sundayCloseTime')->addViolation();
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
        return 'LocationType';
    }


}
