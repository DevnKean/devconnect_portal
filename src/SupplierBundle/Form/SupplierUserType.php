<?php

namespace SupplierBundle\Form;

use AppBundle\Entity\Supplier;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
                ->add('jobTitle', TextType::class);
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $user = $event->getData();
                $form = $event->getForm();

                if (is_null($user->getId())) {
                    $form->add(
                        'plainPassword',
                        RepeatedType::class,
                        array(
                            'type' => PasswordType::class,
                            'first_options' => array('label' => 'Password'),
                            'second_options' => array('label' => 'Repeat Password'),
                        )
                    );
                }
            }
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
        return 'appbundle_user';
    }


}
