<?php

namespace AppBundle\Form;

use AppBundle\Service\SessionBusinessService;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BusinessBeerType extends AbstractType
{
    /**
     * @var SessionBusinessService
     */
    private $sessionBusinessService;

    public function __construct(SessionBusinessService $sessionBusinessService)
    {
        $this->sessionBusinessService = $sessionBusinessService;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => 'AppBundle:BusinessBeerCategory',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.business = :business')
                        ->orderBy('c.name', 'ASC')
                        ->setParameter(':business', $this->sessionBusinessService->getBusiness());
                },
            ])
            ->add('name', TextType::class)
            ->add('notes', TextareaType::class)
        ;
    }
}
