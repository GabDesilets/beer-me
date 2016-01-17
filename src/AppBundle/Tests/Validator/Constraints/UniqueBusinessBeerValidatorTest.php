<?php

namespace AppBundle\Tests\Validator\Constraints;

use AppBundle\Command\Business\Beer\Category\CreateBusinessBeerCategoryCommand;
use AppBundle\Command\Business\Beer\CreateBusinessBeerCommand;
use AppBundle\Command\Business\Beer\Category\EditBusinessBeerCategoryCommand;
use AppBundle\Command\Business\Beer\EditBusinessBeerCommand;
use AppBundle\Entity\Business;
use AppBundle\Entity\BusinessBeer;
use AppBundle\Entity\BusinessBeerCategory;
use AppBundle\Validator\Constraints\UniqueBusiness;
use AppBundle\Validator\Constraints\UniqueBusinessBeerCategoryValidator;
use AppBundle\Validator\Constraints\UniqueBusinessBeerValidator;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Prophecy\Argument;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class UniqueBusinessBeerValidatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var EditBusinessBeerCommand */
    private $editBusinessBeerCommand;

    /** @var CreateBusinessBeerCommand */
    private $createBusinessBeerCommand;

    /** @var UniqueBusinessBeerValidator */
    private $validator;

    /** @var mixed */
    private $businessBeerRepository;

    /** @var mixed */
    private $context;

    /** @var mixed */
    private $beer;

    public function setUp()
    {
        /** @var mixed $em */
        $em = $this->prophesize(EntityManagerInterface::class);

        $this->businessBeerRepository = $this->prophesize(ObjectRepository::class);

        $em->getRepository('AppBundle:BusinessBeer')->willReturn($this->businessBeerRepository);

        $this->context = $this->prophesize(ExecutionContextInterface::class);

        /** @var mixed $violationBuilder */
        $violationBuilder = $this->prophesize(ConstraintViolationBuilderInterface::class);

        $this->context->buildViolation(Argument::any())->willReturn($violationBuilder->reveal());

        $violationBuilder->atPath(Argument::any())->willReturn($violationBuilder->reveal());
        $violationBuilder->addViolation()->willReturn();

        $business = new Business('name', 'address', 'phone', 'administrator-email');
        $category = new BusinessBeerCategory($business, 'category');

        $this->beer = $this->prophesize(BusinessBeer::class);
        $this->beer->getId()->willReturn(1);
        $this->beer->getBusiness()->willReturn($business);

        $this->editBusinessBeerCommand = new EditBusinessBeerCommand();
        $this->editBusinessBeerCommand->id = 1;
        $this->editBusinessBeerCommand->category = $category;
        $this->editBusinessBeerCommand->name = 'name';
        $this->editBusinessBeerCommand->notes = 'notes';

        $this->createBusinessBeerCommand = new CreateBusinessBeerCommand();
        $this->createBusinessBeerCommand->category = $category;
        $this->createBusinessBeerCommand->name = 'name';
        $this->createBusinessBeerCommand->notes = 'notes';

        /** @noinspection PhpParamsInspection */
        $this->validator = new UniqueBusinessBeerValidator($em->reveal());

        /** @noinspection PhpParamsInspection */
        $this->validator->initialize($this->context->reveal());
    }

    public function testValidEdit()
    {
        $this->businessBeerRepository
            ->find($this->editBusinessBeerCommand->id)
            ->willReturn($this->beer->reveal());

        $this->businessBeerRepository
            ->findBy([
                'name' => $this->editBusinessBeerCommand->name,
                'business' => $this->beer->reveal()->getBusiness(),
            ])
            ->shouldBeCalled()
        ;

        $this->context->buildViolation(Argument::any())->shouldNotBeCalled();

        $this->validator->validate($this->editBusinessBeerCommand, new UniqueBusiness());
    }

    public function testValidCreate()
    {
        $this->businessBeerRepository
            ->findBy([
                'name' => $this->createBusinessBeerCommand->name,
                'business' => $this->createBusinessBeerCommand->category->getBusiness(),
            ])
            ->shouldBeCalled()
        ;

        $this->context->buildViolation(Argument::any())->shouldNotBeCalled();

        $this->validator->validate($this->createBusinessBeerCommand, new UniqueBusiness());
    }

    public function testValidSameValuesEdit()
    {
        $this->businessBeerRepository
            ->find($this->editBusinessBeerCommand->id)
            ->willReturn($this->beer->reveal());

        $this->businessBeerRepository
            ->findBy([
                'name' => $this->editBusinessBeerCommand->name,
                'business' => $this->beer->reveal()->getBusiness(),
            ])
            ->willReturn([$this->beer->reveal()])
            ->shouldBeCalled();

        $this->context->buildViolation(Argument::any())->shouldNotBeCalled();

        $this->validator->validate($this->editBusinessBeerCommand, new UniqueBusiness());
    }

    public function testInvalidDifferentValuesEdit()
    {
        /** @var mixed $otherBeer */
        $otherBeer = $this->prophesize(BusinessBeer::class);
        $otherBeer->getId()->willReturn(2);

        $this->businessBeerRepository
            ->find($this->editBusinessBeerCommand->id)
            ->willReturn($this->beer->reveal());

        $this->businessBeerRepository
            ->findBy([
                'name' => $this->editBusinessBeerCommand->name,
                'business' => $this->beer->reveal()->getBusiness(),
            ])
            ->willReturn([$otherBeer->reveal()])
            ->shouldBeCalled();

        $this->context->buildViolation(Argument::any())->shouldBeCalled();

        $this->validator->validate($this->editBusinessBeerCommand, new UniqueBusiness());
    }

    public function testInvalidDifferentValuesCreate()
    {
        /** @var mixed $otherCategory */
        $otherCategory = $this->prophesize(BusinessBeerCategory::class);
        $otherCategory->getId()->willReturn(2);

        $this->businessBeerRepository
            ->findBy([
                'name' => $this->createBusinessBeerCommand->name,
                'business' => $this->createBusinessBeerCommand->category->getBusiness(),
            ])
            ->willReturn([$otherCategory->reveal()])
            ->shouldBeCalled();

        $this->context->buildViolation(Argument::any())->shouldBeCalled();

        $this->validator->validate($this->createBusinessBeerCommand, new UniqueBusiness());
    }
}
