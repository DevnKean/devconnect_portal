<?php

namespace AppBundle\Form;

use AppBundle\Entity\Experience;
use AppBundle\Entity\Reference;
use AppBundle\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReferenceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('companyName')
            ->add('title', TextType::class, [
                'label' => 'Job Title'
            ])
            ->add('email')
            ->add('workPhone')
            ->add('mobilePhone')
            ->add('functions', ChoiceType::class, [
                'choices' => array_combine(Service::getFunctions(), Service::getFunctions()),
                'placeholder' => 'Please select functions',
                'multiple' => true,
                'attr' => [
                    'class' => 'select2',
                    'data-placeholder' => 'Please select functions',
                ]
            ])
            ->add('campaign', TextType::class, [
                'label' => 'Campaign Name',
                'attr' => [
                    'placeholder' => 'What is the campaign called internally?'
                ]
            ])
            ->add('campaignDescription', TextareaType::class, [
                'label' => 'Campaign Description (what work did you do for them?)',
                'attr' => [
                    'placeholder' => 'In your own words, describe the campaign',
                    'rows' => 3,
                    'class' => 'resize-vertically'
                ]
            ]);
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $reference = $event->getData();
            $form = $event->getForm();

            if ($reference->getType() === Reference::TYPE_PAST) {
                $form->add('cessationReason', TextareaType::class, [
                    'attr' => [
                        'placeholder' => 'Please describe why they are no longer your customer',
                        'rows' => 3,
                        'class' => 'resize-vertically'
                    ]
                ]);
            }
        });

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Reference'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_reference';
    }


}
