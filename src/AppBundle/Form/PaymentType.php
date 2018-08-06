<?php

namespace AppBundle\Form;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\Payment;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('invoices', EntityType::class, [
                'class' => Invoice::class,
                'choice_label' => function(Invoice $invoice) {
                    return sprintf('%s (%s - %s)', $invoice->getXeroId(), $invoice->getSupplier(), $invoice->getLead());
                },
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->where('i.status = :status')
                        ->setParameter('status', Invoice::STATUS_PENDING)
                        ->orderBy('i.id', 'ASC');
                },
                'multiple' => true,
                'attr' => [
                    'class' => 'select2 invoice'
                ]
            ])
            ->add('amount',MoneyType::class, [
                'grouping' => true,
                'scale' => 2,
                'currency' => 'AUD',
                'attr' => [
                    'class' => 'input-amount'
                ]
            ])
            ->add('paidAt', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'format' => 'YYYY-MM-dd HH:mm',
                'input' => 'datetime',
                'view_timezone' => 'Australia/Melbourne'
            ])
            ->add('status', ChoiceType::class, [
                'choices' => array_combine(Payment::getPaymentStatus(), Payment::getPaymentStatus())
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Payment'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_payment';
    }


}
