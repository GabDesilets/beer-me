<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BusinessRequestType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('email', EmailType::class)
            ->add('businessName', TextType::class)
            ->add('phoneOne', TextType::class)
            ->add('phoneTwo', TextType::class)
            ->add('phoneThree', TextType::class)
            ->add('address', TextType::class)
            ->add('zipCode', TextType::class, ['max_length' => 6])
            ->add('country', TextType::class)
            ->add('province', TextType::class)
            ->add('city', TextType::class)
        ;
    }
}