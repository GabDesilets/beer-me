<?php

namespace AppBundle\Tests\Validator\Constraints;

use AppBundle\Command\Business\Beer\Category\CreateBusinessBeerCategoryCommand;
use AppBundle\Command\Business\Beer\Category\EditBusinessBeerCategoryCommand;
use AppBundle\Entity\Business;
use AppBundle\Entity\BusinessBeerCategory;
use AppBundle\Validator\Constraints\UniqueBusiness;
use AppBundle\Validator\Constraints\UniqueBusinessBeerCategoryValidator;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Prophecy\Argument;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class UniqueBusinessBeerCategoryValidatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var EditBusinessBeerCategoryCommand */
    private $editBusinessBeerCategoryCommand;

    /** @var CreateBusinessBeerCategoryCommand */
    private $createBusinessBeerCategoryCommand;

    /** @var UniqueBusinessBeerCategoryValidator */
    private $validator;

    /** @var mixed */
    private $businessBeerCategoryRepository;

    /** @var mixed */
    private $context;

    /** @var mixed */
    private $category;

    public function setUp()
    {
        /** @var mixed $em */
        $em = $this->prophesize(EntityManagerInterface::class);

        $this->businessBeerCategoryRepository = $this->prophesize(ObjectRepository::class);

        $em->getRepository('AppBundle:BusinessBeerCategory')->willReturn($this->businessBeerCategoryRepository);

        $this->context = $this->prophesize(ExecutionContextInterface::class);

        /** @var mixed $violationBuilder */
        $violationBuilder = $this->prophesize(ConstraintViolationBuilderInterface::class);

        $this->context->buildViolation(Argument::any())->willReturn($violationBuilder->reveal());

        $violationBuilder->atPath(Argument::any())->willReturn($violationBuilder->reveal());
        $violationBuilder->addViolation()->willReturn();

        $business = new Business('name', 'address', 'phone', 'administrator-email');

        $this->category = $this->prophesize(BusinessBeerCategory::class);
        $this->category->getId()->willReturn(1);
        $this->category->getBusiness()->willReturn($business);

        $this->editBusinessBeerCategoryCommand = new EditBusinessBeerCategoryCommand();
        $this->editBusinessBeerCategoryCommand->id = 1;
        $this->editBusinessBeerCategoryCommand->name = 'name';

        $this->createBusinessBeerCategoryCommand = new CreateBusinessBeerCategoryCommand();
        $this->createBusinessBeerCategoryCommand->business = $business;
        $this->createBusinessBeerCategoryCommand->name = 'name';

        /** @noinspection PhpParamsInspection */
        $this->validator = new UniqueBusinessBeerCategoryValidator($em->reveal());

        /** @noinspection PhpParamsInspection */
        $this->validator->initialize($this->context->reveal());
    }

    public function testValidEdit()
    {
        $this->businessBeerCategoryRepository
            ->find($this->editBusinessBeerCategoryCommand->id)
            ->willReturn($this->category->reveal());

        $this->businessBeerCategoryRepository
            ->findBy([
                'name' => $this->editBusinessBeerCategoryCommand->name,
                'business' => $this->category->reveal()->getBusiness(),
            ])
            ->shouldBeCalled()
        ;

        $this->context->buildViolation(Argument::any())->shouldNotBeCalled();

        $this->validator->validate($this->editBusinessBeerCategoryCommand, new UniqueBusiness());
    }

    public function testValidCreate()
    {
        $this->businessBeerCategoryRepository
            ->findBy([
                'name' => $this->createBusinessBeerCategoryCommand->name,
                'business' => $this->createBusinessBeerCategoryCommand->business,
            ])
            ->shouldBeCalled()
        ;

        $this->context->buildViolation(Argument::any())->shouldNotBeCalled();

        $this->validator->validate($this->createBusinessBeerCategoryCommand, new UniqueBusiness());
    }

    public function testValidSameValuesEdit()
    {
        $this->businessBeerCategoryRepository
            ->find($this->editBusinessBeerCategoryCommand->id)
            ->willReturn($this->category->reveal());

        $this->businessBeerCategoryRepository
            ->findBy([
                'name' => $this->editBusinessBeerCategoryCommand->name,
                'business' => $this->category->reveal()->getBusiness(),
            ])
            ->willReturn([$this->category->reveal()])
            ->shouldBeCalled();

        $this->context->buildViolation(Argument::any())->shouldNotBeCalled();

        $this->validator->validate($this->editBusinessBeerCategoryCommand, new UniqueBusiness());
    }

    public function testInvalidDifferentValuesEdit()
    {
        /** @var mixed $otherCategory */
        $otherCategory = $this->prophesize(BusinessBeerCategory::class);
        $otherCategory->getId()->willReturn(2);

        $this->businessBeerCategoryRepository
            ->find($this->editBusinessBeerCategoryCommand->id)
            ->willReturn($this->category->reveal());

        $this->businessBeerCategoryRepository
            ->findBy([
                'name' => $this->editBusinessBeerCategoryCommand->name,
                'business' => $this->category->reveal()->getBusiness(),
            ])
            ->willReturn([$otherCategory->reveal()])
            ->shouldBeCalled();

        $this->context->buildViolation(Argument::any())->shouldBeCalled();

        $this->validator->validate($this->editBusinessBeerCategoryCommand, new UniqueBusiness());
    }

    public function testInvalidDifferentValuesCreate()
    {
        /** @var mixed $otherCategory */
        $otherCategory = $this->prophesize(BusinessBeerCategory::class);
        $otherCategory->getId()->willReturn(2);

        $this->businessBeerCategoryRepository
            ->findBy([
                'name' => $this->createBusinessBeerCategoryCommand->name,
                'business' => $this->createBusinessBeerCategoryCommand->business,
            ])
            ->willReturn([$otherCategory->reveal()])
            ->shouldBeCalled();

        $this->context->buildViolation(Argument::any())->shouldBeCalled();

        $this->validator->validate($this->createBusinessBeerCategoryCommand, new UniqueBusiness());
    }
}
