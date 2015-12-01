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
            ->add('first_name', TextType::class)
            ->add('last_name', TextType::class)
            ->add('email', TextType::class)
            ->add('business_name', TextType::class)
            ->add('phone_one', NumberType::class)
            ->add('phone_two', NumberType::class)
            ->add('phone_three', NumberType::class)
            ->add('address', TextType::class)
            ->add('zip_code', TextType::class)
            ->add('country', TextType::class)
            ->add('province', TextType::class)
            ->add('city', TextType::class)
        ;
    }
}