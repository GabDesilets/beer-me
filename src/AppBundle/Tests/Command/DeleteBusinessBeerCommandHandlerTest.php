<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\DeleteBusinessBeerCategoryCommand;
use AppBundle\Command\DeleteBusinessBeerCategoryCommandHandler;
use AppBundle\Command\DeleteBusinessBeerCommand;
use AppBundle\Command\DeleteBusinessBeerCommandHandler;
use AppBundle\Entity\Business;
use AppBundle\Entity\BusinessBeer;
use AppBundle\Entity\BusinessBeerCategory;
use AppBundle\Event\BusinessBeerCategoryDeletedEvent;
use AppBundle\Event\BusinessBeerDeletedEvent;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Prophecy\Argument;
use SimpleBus\Message\Recorder\RecordsMessages;

class DeleteBusinessBeerCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var DeleteBusinessBeerCommandHandler */
    private $handler;

    /** @var mixed */
    private $recorder;

    /** @var mixed */
    private $entityManager;

    /** @var mixed */
    private $businessBeerRepository;

    /** @var DeleteBusinessBeerCommand */
    private $command;

    /** @var BusinessBeer */
    private $beer;

    public function setUp()
    {
        $this->recorder = $this->prophesize(RecordsMessages::class);
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->businessBeerRepository = $this->prophesize(ObjectRepository::class);

        $this->command = new DeleteBusinessBeerCommand();
        $this->command->id = 1;

        $this->beer = new BusinessBeer(
            new BusinessBeerCategory(
                new Business('name', 'address', 'phone', 'email'),
                'category'
            ),
            'name',
            'notes'
        );

        /** @noinspection PhpParamsInspection */
        $this->handler = new DeleteBusinessBeerCommandHandler(
            $this->recorder->reveal(),
            $this->entityManager->reveal()
        );
    }

    public function testDeleteExists()
    {
        $this->entityManager
            ->getRepository('AppBundle:BusinessBeer')
            ->willReturn($this->businessBeerRepository)
            ->shouldBeCalled();

        $this->businessBeerRepository->find($this->command->id)->willReturn($this->beer)->shouldBeCalled();

        $this->entityManager->remove($this->beer)->shouldBeCalled();
        $this->entityManager->flush()->shouldBeCalled();
        $this->recorder->record(new BusinessBeerDeletedEvent($this->beer))->shouldBeCalled();

        $this->handler->handle($this->command);
    }

    public function testDeleteNotFound()
    {
        $this->entityManager
            ->getRepository('AppBundle:BusinessBeer')
            ->willReturn($this->businessBeerRepository)
            ->shouldBeCalled();

        $this->businessBeerRepository->find($this->command->id)->willReturn(null)->shouldBeCalled();

        $this->entityManager->remove(Argument::any())->shouldNotBeCalled();
        $this->entityManager->flush()->shouldNotBeCalled();
        $this->recorder->record($this->beer)->shouldNotBeCalled();

        $this->handler->handle($this->command);
    }
}
