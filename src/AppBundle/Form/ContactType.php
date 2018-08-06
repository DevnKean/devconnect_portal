<?php

namespace AppBundle\Form;

use AppBundle\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('type', HiddenType::class, [
                ])
                ->add('firstName', TextType::class, [
                    'attr' => [
                        'class' => 'first-name'
                    ],
                    'label' => 'First Name',
                    'required' => true,
                    'constraints' => [
                        new NotBlank()
                    ]
                ])
                ->add('lastName', TextType::class, [
                    'attr' => [
                        'class' => 'last-name'
                    ],
                    'label' => 'Last Name',
                    'required' => true,
                    'constraints' => [
                        new NotBlank()
                    ]
                ])
                ->add('email', TextType::class, [
                    'attr' => [
                        'class' => 'email'
                    ],
                    'label' => 'Email Phone',
                    'required' => true,
                    'constraints' => [
                        new Email()
                    ]
                ])
                ->add('workPhone', TextType::class, [
                    'attr' => [
                        'class' => 'work-phone'
                    ],
                    'label' => 'Work Phone',
                    'required' => true,
                    'constraints' => [
                        new NotBlank()
                    ]
                ])
                ->add('mobilePhone', TextType::class, [
                    'attr' => [
                        'class' => 'mobile-phone'
                    ],
                    'label' => 'Mobile Phone',
                    'required' => true,
                    'constraints' => [
                        new NotBlank()
                    ]
                ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Contact',
            'constraints' => [
                new Callback(function (Contact $contact, ExecutionContextInterface $context, $payload) {
                    if (!is_numeric($contact->getMobilePhone())) {
                        $context->buildViolation('Mobile phone is in wrong format')
                                ->atPath('mobilePhone')->addViolation();
                    }

                    if (!is_numeric($contact->getWorkPhone())) {
                        $context->buildViolation('Work phone is in wrong format')
                                ->atPath('workPhone')->addViolation();
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
        return 'ContactType';
    }


}
