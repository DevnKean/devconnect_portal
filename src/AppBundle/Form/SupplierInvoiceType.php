<?php

namespace AppBundle\Form;

use AppBundle\Entity\LeadSupplier;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplierInvoiceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('leadSupplier', EntityType::class, [
                'class' => LeadSupplier::class,
                'choice_label' => function(LeadSupplier $leadSupplier) {
                    return "{$leadSupplier->getLead()->getBusinessName()} - {$leadSupplier->getLead()} ( {$leadSupplier->getSupplier()} )";
                },
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('ls')
                              ->where('ls.result = :result')
                              ->join('ls.supplier', 's')
                              ->orderBy('s.businessName', 'ASC')
                              ->setParameter('result', LeadSupplier::RESULT_SUCCESS);
                },
                'label' => 'Campaign',
                'placeholder' => 'Please Select Campaign'
            ])
            ->add('issuedAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date the Supplier issued invoice to customer'
            ])
            ->add('receivedAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date the Supplier invoice was received by CX Connect'
            ])
            ->add('paymentDueAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Due date for customer payment to Supplier'
            ])
            ->add('total', MoneyType::class, [
                'grouping' => true,
                'scale' => 2,
                'currency' => 'AUD',
                'label' => 'Invoice total ex GST'
            ])
            ->add('referenceNumber', TextType::class, [
                'label' => 'Supplier invoice reference number'
            ])
            ->add('file', FileType::class, [
                'label' => 'Upload invoice',
                'data_class' => null,
                'required' => is_null($builder->getData()->getId())
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\SupplierInvoice'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_supplierinvoice';
    }


}
