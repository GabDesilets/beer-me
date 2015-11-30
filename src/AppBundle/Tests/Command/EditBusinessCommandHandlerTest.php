<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\EditBusinessCommand;
use AppBundle\Command\EditBusinessCommandHandler;
use AppBundle\Entity\Business;
use AppBundle\Event\BusinessUpdatedEvent;
use AppBundle\Exception\BusinessNotFoundException;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Prophecy\Argument;
use SimpleBus\Message\Recorder\RecordsMessages;

class EditBusinessCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var EditBusinessCommandHandler */
    private $handler;

    /** @var mixed */
    private $recorder;

    /** @var mixed */
    private $entityManager;

    /** @var mixed */
    private $businessRepository;

    /** @var EditBusinessCommand */
    private $command;

    public function setUp()
    {
        $this->recorder = $this->prophesize(RecordsMessages::class);
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->businessRepository = $this->prophesize(ObjectRepository::class);

        $this->command = new EditBusinessCommand();
        $this->command->id = 1;
        $this->command->phone = 'phone';
        $this->command->name = 'name';
        $this->command->address = 'address';
        $this->command->administratorEmail = 'email';

        /** @noinspection PhpParamsInspection */
        $this->handler = new EditBusinessCommandHandler(
            $this->recorder->reveal(),
            $this->entityManager->reveal()
        );
    }

    public function testUpdateExists()
    {
        $business = new Business('name2', 'address2', 'phone2', 'email2');

        $this->entityManager
            ->getRepository('AppBundle:Business')
            ->willReturn($this->businessRepository)
            ->shouldBeCalled();

        $this->businessRepository->find($this->command->id)->willReturn($business)->shouldBeCalled();

        $this->entityManager->flush()->shouldBeCalled();

        $this->recorder->record(Argument::that(function(BusinessUpdatedEvent $event) use ($business) {
            $compareBusiness = $event->getBusiness();
            return $compareBusiness === $business
                && $this->command->administratorEmail == $compareBusiness->getAdministratorUser()->getEmail()
                && $this->command->name == $compareBusiness->getName()
                && $this->command->address == $compareBusiness->getAddress()
                && $this->command->phone == $compareBusiness->getPhone()
            ;
        }))->shouldBeCalled();

        $this->handler->handle($this->command);
    }

    public function testUpdateNotFound()
    {
        $this->entityManager
            ->getRepository('AppBundle:Business')
            ->willReturn($this->businessRepository)
            ->shouldBeCalled();

        $this->businessRepository->find($this->command->id)->willReturn(null)->shouldBeCalled();

        $this->setExpectedException(BusinessNotFoundException::class);

        $this->entityManager->flush()->shouldNotBeCalled();
        $this->recorder->record(Argument::any())->shouldNotBeCalled();

        $this->handler->handle($this->command);
    }
}
