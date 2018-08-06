<?php

namespace AppBundle\Form;

use AppBundle\Entity\Contract;
use AppBundle\Entity\Service;
use AppBundle\Entity\Supplier;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplierType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('businessName')
                ->add('tradingName')
                ->add('abnNumber', TextType::class, [
                    'label' => 'ABN number'
                ])
                ->add('supplierNotes', CollectionType::class, [
                    'entry_type' => SupplierNoteType::class,
                    'label'=> false,
                    'entry_options' => [
                        'label' => false
                    ]
                ])
        ->add('save', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary pull-right'
            ]
        ]);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) {
            $supplier = $event->getData();
            $form = $event->getForm();

            if ($supplier && null !== $supplier->getId()) {
                $form
                    ->add('status', ChoiceType::class, [
                    'choices' => Supplier::getStatues()
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
            'data_class' => 'AppBundle\Entity\Supplier'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_supplier';
    }


}
