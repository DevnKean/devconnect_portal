<?php

namespace AppBundle\Form;

use AppBundle\Entity\CommissionModel;
use AppBundle\Entity\Service;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractServiceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                              ->orderBy('s.name', 'ASC');
                }
            ])
            ->add('commissionModel', EntityType::class, [
                'class' => CommissionModel::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                              ->orderBy('s.name', 'ASC');
                },
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ContractService'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ContractServiceType';
    }


}
