<?php

namespace DemacMedia\Bundle\PhysicalStoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PhysicalStoreOrdersType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'invno',
                'text',
                [
                    'required' => true,
                    'label'    => 'Invno'
                ]
            )
            ->add(
                'custno',
                'text',
                [
                    'required'    => true,
                    'label'       => 'Custno',
                ]
            )
            ->add(
                'invdate',
                'oro_datetime',
                [
                    'required'    => false,
                    'label'       => 'Invdate',
                ]
            )
            ->add(
                'shipvia',
                'text',
                [
                    'required'    => false,
                    'label'       => 'Shipvia',
                ]
            )
            ->add(
                'cshipno',
                'text',
                [
                    'required'    => false,
                    'label'       => 'Cshipno',
                ]
            )
            ->add(
                'taxrate',
                'text',
                [
                    'required'    => false,
                    'label'       => 'Tax Rate',
                ]
            )
            ->add(
                'tax',
                'text',
                [
                    'required'    => false,
                    'label'       => 'Tax',
                ]
            )
            ->add(
                'invamt',
                'text',
                [
                    'required'    => true,
                    'label'       => 'Invamt',
                ]
            )
            ->add(
                'ponum',
                'text',
                [
                    'required'    => false,
                    'label'       => 'Ponum',
                ]
            )
            ->add(
                'refno',
                'text',
                [
                    'required'    => false,
                    'label'       => 'Refno',
                ]
            )
            ->add(
                'created',
                'oro_datetime',
                [
                    'required'    => false,
                    'label'       => 'Created At',
                ]
            )
            ->add(
                'updated',
                'oro_datetime',
                [
                    'required'    => false,
                    'label'       => 'Updated At',
                ]
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'           => 'DemacMedia\Bundle\PhysicalStoreBundle\Entity\OroPhysicalStoreOrders',
                'csrf_protection'      => false,
                'cascade_validation'   => false,
                'extra_fields_message' => 'EXTRA FIELDS DETECTED! "{{ extra_fields }}"',
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'demacmedia_physicalstore_orders';
    }
}
