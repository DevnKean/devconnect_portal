<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AwardType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Award Name',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('organisation', TextType::class, [
                'label' => 'Organisation issuing the award',
                'required' => false,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'empty_data' => null,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description (tell us about your award)',
                'required' => false,
                'attr' => [
                    'rows' => 10
                ],
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
            'data_class' => 'AppBundle\Entity\Award'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_award';
    }


}
