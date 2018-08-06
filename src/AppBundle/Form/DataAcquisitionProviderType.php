<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class DataAcquisitionProviderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company', TextType::class, [
                'label' => 'Company',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('website', UrlType::class, [
                'attr' => [
                    'placeholder' => 'Please enter a full URL like https://www.google.com'
                ],
                'label' => 'Website',
                'constraints' => [
                    new NotBlank(),
                    new Url()
                ]
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DataAcquisitionProvider'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_dataacquisitionprovider';
    }


}
