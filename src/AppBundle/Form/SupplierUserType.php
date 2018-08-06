<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplierUserType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class)
                ->add('email', EmailType::class)
                ->add('firstName', TextType::class)
                ->add('lastName', TextType::class)
                ->add('plainPassword', RepeatedType::class, [
                        'type' => PasswordType::class,
                        'required' => false,
                        'invalid_message' => 'The password fields must match.',
                        'first_options' => array('label' => 'Password'),
                        'second_options' => array('label' => 'Repeat Password'),
                    ]
                );

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\User',
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'supplierbundle_user';
    }


}
