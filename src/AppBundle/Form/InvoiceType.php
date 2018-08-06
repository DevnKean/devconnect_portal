<?php

namespace AppBundle\Form;

use AppBundle\Entity\Lead;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\LeadSupplier;
use AppBundle\Entity\Supplier;
use AppBundle\Entity\SupplierInvoice;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('supplierInvoice', EntityType::class, [
                'class' => SupplierInvoice::class,
                'choice_label' => function(SupplierInvoice $supplierInvoice) {
                    return "{$supplierInvoice->getReferenceNumber()} ( {$supplierInvoice->getLeadSupplier()->getSupplier()} )";
                },
                'query_builder' => function(EntityRepository $er) {
                    $qb = $er->createQueryBuilder('si');
                    $qb->leftJoin('si.invoice', 'i')
                        ->where($qb->expr()->isNull('i.id'))
                        ->orderBy('si.referenceNumber', 'ASC');
                    return $qb;
                },
                'placeholder' => 'Please Select Supplier Invoice',
                'attr' => [
                    'class' => 'supplier-invoice'
                ]
            ])
            ->add('xeroId', TextType::class, [
                    'label' => 'XERO ID'
            ])
            ->add('sentToSupplierAt', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'format' => 'YYYY-MM-dd HH:mm',
                'input' => 'datetime',
                'view_timezone' => 'Australia/Melbourne',
                'label' => 'Date/time invoice sent to customer in XERO'
            ])
            ->add('nextInvoiceIssueAt', DateType::class, [
                'label' => 'Expected date next invoice copy should be received from Supplier',
                'widget' => 'single_text'
            ]);


    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Invoice'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_invoice';
    }


}
