<?php

namespace AppBundle\Form;

use AppBundle\Entity\Commercial;
use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommercialType extends AbstractType
{
    /**
     * @var User
     */
    private $user;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->user = $tokenStorage->getToken()->getUser();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('models', ChoiceType::class, [
                'choices' => array_combine(Commercial::getCommercialModels($this->user->getSupplier()), Commercial::getCommercialModels($this->user->getSupplier())),
                'label' => false,
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'data-placeholder' => 'Please select commercial modal'
                ]
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Commercial'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_commercial';
    }
}
