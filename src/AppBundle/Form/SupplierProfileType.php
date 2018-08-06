<?php

namespace AppBundle\Form;

use AppBundle\Entity\SupplierProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplierProfileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var SupplierProfile $supplierProfile */
        $supplierProfile = $builder->getData();
        $builder
            ->add('isDisabled', ChoiceType::class, [
                'choices' => [
                    'Yes' => 0,
                    'No' => 1
                ],
                'label' => $supplierProfile->getProfile()->getDisabledText(),
                'attr' => [
                    'class' => 'profile-check'
                ],
                'data' => $supplierProfile->getIsDisabled()
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\SupplierProfile'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_supplierprofile';
    }


}
