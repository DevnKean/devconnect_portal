<?php

namespace AppBundle\Form;

use AppBundle\Entity\Lead;
use AppBundle\Entity\Service;
use AppBundle\Repository\LeadRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LeadApproveType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', ChoiceType::class, [
                'choices' => array_combine(Lead::getStatuses(), Lead::getStatuses())
            ])
            ->add('type', ChoiceType::class, [
                'choices' => array_combine(Lead::getTypes(), Lead::getTypes()),
                'label' => 'Type of Lead'
            ])
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.name', 'ASC');
                },
                'placeholder' => 'Please select a service'
            ])
            ->add('function', ChoiceType::class, [
                'choices' => array_combine(Service::getFunctions(), Service::getFunctions()),
                'multiple' => true,
                'placeholder' => 'Please select functions',
                'attr' => [
                    'class' => 'select2',
                    'data-placeholder' => 'Please select functions'
                ]
            ]);

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Lead'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_leadapprove';
    }


}
