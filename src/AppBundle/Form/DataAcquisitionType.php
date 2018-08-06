<?php

namespace AppBundle\Form;

use AppBundle\Entity\DataAcquisition;
use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DataAcquisitionType extends AbstractType
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
        $builder->add('experience', ChoiceType::class, [
            'label' => 'What experience do you have in sourcing data for outbound campaigns?',
            'choices' => array_combine(DataAcquisition::getExperiences($this->user->getSupplier()), DataAcquisition::getExperiences($this->user->getSupplier())),
            'expanded' => true
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DataAcquisition'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_dataacquisition';
    }


}
