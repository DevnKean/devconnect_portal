<?php

namespace SupplierBundle\Form;

use AppBundle\Entity\Supplier;
use AppBundle\Form\SimpleGooglePlaceAutocompleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class SupplierInformationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('businessName', TextType::class, [
                'required' => true
            ])
            ->add('tradingName', TextType::class, [
                'required' => true
            ])
            ->add('abnNumber', TextType::class, [
                'label' => 'ABN Number'
            ])
            ->add('address', SimpleGooglePlaceAutocompleteType::class, [
                'required' => true
            ])
            ->add('website', UrlType::class, [
                'attr' => [
                    'placeholder' => 'Please enter a full URL like https://www.google.com'
                ],
                'required' => false
            ])
            ->add('linkedin', UrlType::class, [
                'label' => 'LinkedIn',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Please enter a full URL like https://www.google.com'
                ]
            ])
            ->add('twitter', UrlType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Please enter a full URL like https://www.google.com'
                ],
            ])
            ->add('youtube', UrlType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Please enter a full URL like https://www.google.com'
                ],
                'validation_groups' => [

                ]
            ])
            ->add('instagram', UrlType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Please enter a full URL like https://www.google.com'
                ],
            ])
            ->add('snapchat', UrlType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Please enter a full URL like https://www.google.com'
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Supplier'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'supplierbundle_information';
    }


}
