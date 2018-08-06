<?php

namespace AppBundle\Form;

use AppBundle\Entity\Experience;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ExperienceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('function', HiddenType::class)
            ->add('yearsExperience', ChoiceType::class, [
                'label' => 'Years of Experience',
                'choices' => Experience::getYears(),
                'required' => false,
                'placeholder' => 'Please select...',
                'attr' => [
                    'class' => 'year-experience'
                ]
            ])
            ->add('selfRating', ChoiceType::class, [
                'label' => 'Self Rating',
                'choices' => array_combine(Experience::getSelfRatings(), Experience::getSelfRatings()),
                'placeholder' => 'Please select...',
                'required' => false,
                'attr' => [
                    'class' => 'self-rating'
                ]
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Experience',
            'constraints' => [
                new Callback(function (Experience $experience, ExecutionContextInterface $context, $payload) {
                    if (empty($experience->getYearsExperience())) {
                        $context->buildViolation('You must select an option from the drop-down box')
                                ->atPath('yearsExperience')->addViolation();
                    }

                    if ($experience->getYearsExperience() == 'Nil' && !empty($experience->getSelfRating())) {
                        $context->buildViolation('You must select an option from the drop-down box')
                                ->atPath('yearsExperience')->addViolation();
                    }

                    if ($experience->getYearsExperience() != 'Nil' && empty($experience->getSelfRating())) {
                        $context->buildViolation('Please select a self-rating from the drop-down list')
                                ->atPath('selfRating')->addViolation();
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
        return 'appbundle_experience';
    }


}
