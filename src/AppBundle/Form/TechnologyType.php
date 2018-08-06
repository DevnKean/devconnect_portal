<?php

namespace AppBundle\Form;

use AppBundle\Entity\Technology;
use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class TechnologyType extends AbstractType
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
            ->add('technology', TextType::class)
            ->add('vendor', TextType::class, [
                'attr' => [
                    'placeholder' => 'Vendor',
                    'class' => 'vendor'
                ],
            ])
            ->add('experienceLevel', ChoiceType::class, [
                'required' => true,
                'choices' => Technology::getExperienceLevels($this->user->getSupplier()),
                'placeholder' => 'Please Select Experience Level...',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Experience Level is required'
                    ])
                ],
                'attr' => [
                    'class' => 'experience-level'
                ]
            ])
            ->add('type', HiddenType::class, [
            ]);
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Technology',
            'constraints' => [
                new Callback(function(Technology $data, ExecutionContextInterface $context, $payload) {
                    if ($data->getExperienceLevel() !== 'We don\'t have it yet' && empty($data->getVendor())) {
                        $context->buildViolation('Vendor Name is required')
                                ->atPath('vendor')->addViolation();
                    }

                    if ($data->getType() == Technology::TYPE_CUSTOM && empty($data->getTechnology())) {
                        $context->buildViolation('Technology Name is required')
                                ->atPath('technology')->addViolation();
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
        return 'TechnologyType';
    }


}
