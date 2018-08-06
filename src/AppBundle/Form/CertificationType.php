<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CertificationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Certification Name',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('number', TextType::class, [
                'label' => 'Certification Number',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'rows' => 4,
                    'placeholder' => 'Please provide a brief description that explains the certification'
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
            'data_class' => 'AppBundle\Entity\Certification'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'CertificationType';
    }


}
