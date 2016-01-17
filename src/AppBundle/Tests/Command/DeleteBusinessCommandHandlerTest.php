<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\Business\DeleteBusinessCommand;
use AppBundle\Command\Business\DeleteBusinessCommandHandler;
use AppBundle\Entity\Business;
use AppBundle\Event\BusinessDeletedEvent;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Prophecy\Argument;
use SimpleBus\Message\Recorder\RecordsMessages;

class DeleteBusinessCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var DeleteBusinessCommandHandler */
    private $handler;

    /** @var mixed */
    private $recorder;

    /** @var mixed */
    private $entityManager;

    /** @var mixed */
    private $businessRepository;

    /** @var DeleteBusinessCommand */
    private $command;

    /** @var Business */
    private $business;

    public function setUp()
    {
        $this->recorder = $this->prophesize(RecordsMessages::class);
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->businessRepository = $this->prophesize(ObjectRepository::class);

        $this->command = new DeleteBusinessCommand();
        $this->command->id = 1;

        $this->business = new Business('name', 'address', 'phone', 'email');

        /** @noinspection PhpParamsInspection */
        $this->handler = new DeleteBusinessCommandHandler(
            $this->recorder->reveal(),
            $this->entityManager->reveal()
        );
    }

    public function testDeleteExists()
    {
        $this->entityManager
            ->getRepository('AppBundle:Business')
            ->willReturn($this->businessRepository)
            ->shouldBeCalled();

        $this->businessRepository->find($this->command->id)->willReturn($this->business)->shouldBeCalled();

        $this->entityManager->remove($this->business)->shouldBeCalled();
        $this->entityManager->flush()->shouldBeCalled();
        $this->recorder->record(new BusinessDeletedEvent($this->business))->shouldBeCalled();

        $this->handler->handle($this->command);
    }

    public function testDeleteNotFound()
    {
        $this->entityManager
            ->getRepository('AppBundle:Business')
            ->willReturn($this->businessRepository)
            ->shouldBeCalled();

        $this->businessRepository->find($this->command->id)->willReturn(null)->shouldBeCalled();

        $this->entityManager->remove(Argument::any())->shouldNotBeCalled();
        $this->entityManager->flush()->shouldNotBeCalled();
        $this->recorder->record($this->business)->shouldNotBeCalled();

        $this->handler->handle($this->command);
    }
}
