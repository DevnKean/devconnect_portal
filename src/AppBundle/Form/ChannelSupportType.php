<?php

namespace AppBundle\Form;

use AppBundle\Entity\ChannelSupport;
use AppBundle\Entity\Technology;
use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ChannelSupportType extends AbstractType
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
            ->add('channel', TextType::class)
            ->add('experienceLevel', ChoiceType::class, [
                'choices' => ChannelSupport::getChannelSupports($this->user->getSupplier()),
                'placeholder' => 'Please Select Experience Level...'
            ])
            ->add('type', HiddenType::class, [
            ]);;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ChannelSupport'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ChannelSupportType';
    }


}
