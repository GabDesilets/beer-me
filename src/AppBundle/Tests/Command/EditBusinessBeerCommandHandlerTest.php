<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\EditBusinessBeerCommand;
use AppBundle\Command\EditBusinessBeerCommandHandler;
use AppBundle\Entity\Business;
use AppBundle\Entity\BusinessBeer;
use AppBundle\Entity\BusinessBeerCategory;
use AppBundle\Event\BusinessBeerUpdatedEvent;
use AppBundle\Exception\BusinessBeerNotFoundException;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Prophecy\Argument;
use SimpleBus\Message\Recorder\RecordsMessages;

class EditBusinessBeerCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var EditBusinessBeerCommandHandler */
    private $handler;

    /** @var mixed */
    private $recorder;

    /** @var mixed */
    private $entityManager;

    /** @var mixed */
    private $businessBeerRepository;

    /** @var EditBusinessBeerCommand */
    private $command;

    public function setUp()
    {
        $this->recorder = $this->prophesize(RecordsMessages::class);
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->businessBeerRepository = $this->prophesize(ObjectRepository::class);

        $this->command = new EditBusinessBeerCommand();
        $this->command->id = 1;
        $this->command->name = 'name';
        $this->command->notes = 'notes';
        $this->command->category = new BusinessBeerCategory(
            new Business('name', 'address', 'phone', 'administratorEmail'),
            'category'
        );

        /** @noinspection PhpParamsInspection */
        $this->handler = new EditBusinessBeerCommandHandler(
            $this->recorder->reveal(),
            $this->entityManager->reveal()
        );
    }

    public function testUpdateExists()
    {
        $beer = new BusinessBeer(
            new BusinessBeerCategory(
                new Business('name2', 'address2', 'phone2', 'email2'),
                'category2'
            ),
            'name2',
            'notes2'
        );

        $this->entityManager
            ->getRepository('AppBundle:BusinessBeer')
            ->willReturn($this->businessBeerRepository)
            ->shouldBeCalled();

        $this->businessBeerRepository->find($this->command->id)->willReturn($beer)->shouldBeCalled();

        $this->entityManager->flush()->shouldBeCalled();

        $this->recorder->record(Argument::that(function (BusinessBeerUpdatedEvent $event) use ($beer) {
            $compareBeer = $event->getBeer();
            return $compareBeer === $beer
                && $this->command->name == $beer->getName()
                && $this->command->notes == $beer->getNotes()
                && $this->command->category == $beer->getCategory()
            ;
        }))->shouldBeCalled();

        $this->handler->handle($this->command);
    }

    public function testUpdateNotFound()
    {
        $this->entityManager
            ->getRepository('AppBundle:BusinessBeer')
            ->willReturn($this->businessBeerRepository)
            ->shouldBeCalled();

        $this->businessBeerRepository->find($this->command->id)->willReturn(null)->shouldBeCalled();

        $this->setExpectedException(BusinessBeerNotFoundException::class);

        $this->entityManager->flush()->shouldNotBeCalled();
        $this->recorder->record(Argument::any())->shouldNotBeCalled();

        $this->handler->handle($this->command);
    }
}
