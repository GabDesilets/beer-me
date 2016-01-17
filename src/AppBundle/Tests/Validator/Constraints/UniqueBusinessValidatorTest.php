<?php

namespace AppBundle\Tests\Validator\Constraints;

use AppBundle\Command\Business\CreateBusinessCommand;
use AppBundle\Command\Business\EditBusinessCommand;
use AppBundle\Entity\Business;
use AppBundle\Entity\User;
use AppBundle\Validator\Constraints\UniqueBusiness;
use AppBundle\Validator\Constraints\UniqueBusinessValidator;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Prophecy\Argument;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class UniqueBusinessValidatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var EditBusinessCommand */
    private $value;

    /** @var UniqueBusinessValidator */
    private $validator;

    /** @var mixed */
    private $businessRepository;

    /** @var mixed */
    private $userRepository;

    /** @var mixed */
    private $context;

    /** @var mixed */
    private $business;

    /** @var mixed */
    private $user;

    public function setUp()
    {
        /** @var mixed $em */
        $em = $this->prophesize(EntityManagerInterface::class);

        $this->businessRepository = $this->prophesize(ObjectRepository::class);
        $this->userRepository = $this->prophesize(ObjectRepository::class);

        $em->getRepository('AppBundle:Business')->willReturn($this->businessRepository);
        $em->getRepository('AppBundle:User')->willReturn($this->userRepository);

        $this->context = $this->prophesize(ExecutionContextInterface::class);

        /** @var mixed $violationBuilder */
        $violationBuilder = $this->prophesize(ConstraintViolationBuilderInterface::class);

        $this->context->buildViolation(Argument::any())->willReturn($violationBuilder->reveal());

        $violationBuilder->atPath(Argument::any())->willReturn($violationBuilder->reveal());
        $violationBuilder->addViolation()->willReturn();

        $this->business = $this->prophesize(Business::class);
        $this->user = $this->prophesize(User::class);

        $this->business->getAdministratorUser()->willReturn($this->user->reveal());

        $this->value = new EditBusinessCommand();
        $this->value->id = 1;
        $this->value->name = 'name';
        $this->value->address = 'address';
        $this->value->phone = 'phone';
        $this->value->administratorEmail = 'email';

        /** @noinspection PhpParamsInspection */
        $this->validator = new UniqueBusinessValidator($em->reveal());

        /** @noinspection PhpParamsInspection */
        $this->validator->initialize($this->context->reveal());
    }

    public function testValid()
    {
        $this->businessRepository->find(1)->willReturn($this->business->reveal());
        $this->businessRepository->findBy(['name' => $this->value->name])->shouldBeCalled();
        $this->businessRepository->findBy(['address' => $this->value->address])->shouldBeCalled();
        $this->businessRepository->findBy(['phone' => $this->value->phone])->shouldBeCalled();
        $this->userRepository->findBy(['email' => $this->value->administratorEmail])->shouldBeCalled();

        $this->context->buildViolation(Argument::any())->shouldNotBeCalled();

        $this->validator->validate($this->value, new UniqueBusiness());
    }

    public function testValidSameValues()
    {
        $this->business->getId()->willReturn(1);
        $this->user->getId()->willReturn(2);

        $this->businessRepository->find(1)->willReturn($this->business->reveal());

        $this->businessRepository->findBy(['name' => $this->value->name])
            ->willReturn([$this->business->reveal()])
            ->shouldBeCalled();

        $this->businessRepository->findBy(['address' => $this->value->address])
            ->willReturn([$this->business->reveal()])
            ->shouldBeCalled();

        $this->businessRepository->findBy(['phone' => $this->value->phone])
            ->willReturn([$this->business->reveal()])
            ->shouldBeCalled();

        $this->userRepository->findBy(['email' => $this->value->administratorEmail])
            ->willReturn([$this->user->reveal()])
            ->shouldBeCalled();

        $this->context->buildViolation(Argument::any())->shouldNotBeCalled();

        $this->validator->validate($this->value, new UniqueBusiness());
    }

    public function testInvalidDifferentValues()
    {
        $this->business->getId()->willReturn(1);
        $this->user->getId()->willReturn(2);

        /** @var mixed $otherBusiness */
        $otherBusiness = $this->prophesize(Business::class);

        /** @var mixed $otherUser */
        $otherUser = $this->prophesize(User::class);

        $otherBusiness->getAdministratorUser()->willReturn($otherUser);
        $otherBusiness->getId()->willReturn(2);
        $otherUser->getId()->willReturn(1);

        $this->businessRepository->find(1)->willReturn($this->business->reveal());

        $this->businessRepository->findBy(['name' => $this->value->name])
            ->willReturn([$otherBusiness->reveal()])
            ->shouldBeCalled();

        $this->businessRepository->findBy(['address' => $this->value->address])
            ->willReturn([$otherBusiness->reveal()])
            ->shouldBeCalled();

        $this->businessRepository->findBy(['phone' => $this->value->phone])
            ->willReturn([$otherBusiness->reveal()])
            ->shouldBeCalled();

        $this->userRepository->findBy(['email' => $this->value->administratorEmail])
            ->willReturn([$otherUser->reveal()])
            ->shouldBeCalled();

        $this->context->buildViolation(Argument::any())->shouldBeCalledTimes(4);

        $this->validator->validate($this->value, new UniqueBusiness());
    }

    public function testNewInstance()
    {
        $value = new CreateBusinessCommand();

        $this->businessRepository->findBy(['name' => $value->name])->shouldBeCalled();
        $this->businessRepository->findBy(['address' => $value->address])->shouldBeCalled();
        $this->businessRepository->findBy(['phone' => $value->phone])->shouldBeCalled();
        $this->userRepository->findBy(['email' => $value->administratorEmail])->shouldBeCalled();

        $this->businessRepository->find(Argument::any())->shouldNotBeCalled();

        $this->validator->validate($value, new UniqueBusiness());
    }
}
