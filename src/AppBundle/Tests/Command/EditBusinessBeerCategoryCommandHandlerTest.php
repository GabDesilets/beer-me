<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\EditBusinessBeerCategoryCommand;
use AppBundle\Command\EditBusinessBeerCategoryCommandHandler;
use AppBundle\Entity\Business;
use AppBundle\Entity\BusinessBeerCategory;
use AppBundle\Event\BusinessBeerCategoryUpdatedEvent;
use AppBundle\Exception\BusinessBeerCategoryNotFoundException;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Prophecy\Argument;
use SimpleBus\Message\Recorder\RecordsMessages;

class EditBusinessBeerCategoryCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var EditBusinessBeerCategoryCommandHandler */
    private $handler;

    /** @var mixed */
    private $recorder;

    /** @var mixed */
    private $entityManager;

    /** @var mixed */
    private $businessBeerCategoryRepository;

    /** @var EditBusinessBeerCategoryCommand */
    private $command;

    public function setUp()
    {
        $this->recorder = $this->prophesize(RecordsMessages::class);
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->businessBeerCategoryRepository = $this->prophesize(ObjectRepository::class);

        $this->command = new EditBusinessBeerCategoryCommand();
        $this->command->id = 1;
        $this->command->name = 'name';

        /** @noinspection PhpParamsInspection */
        $this->handler = new EditBusinessBeerCategoryCommandHandler(
            $this->recorder->reveal(),
            $this->entityManager->reveal()
        );
    }

    public function testUpdateExists()
    {
        $category = new BusinessBeerCategory(
            new Business('name2', 'address2', 'phone2', 'email2'),
            'category'
        );

        $this->entityManager
            ->getRepository('AppBundle:BusinessBeerCategory')
            ->willReturn($this->businessBeerCategoryRepository)
            ->shouldBeCalled();

        $this->businessBeerCategoryRepository->find($this->command->id)->willReturn($category)->shouldBeCalled();

        $this->entityManager->flush()->shouldBeCalled();

        $this->recorder->record(Argument::that(function (BusinessBeerCategoryUpdatedEvent $event) use ($category) {
            $compareCategory = $event->getCategory();
            return $compareCategory === $category
                && $this->command->name == $compareCategory->getName()
            ;
        }))->shouldBeCalled();

        $this->handler->handle($this->command);
    }

    public function testUpdateNotFound()
    {
        $this->entityManager
            ->getRepository('AppBundle:BusinessBeerCategory')
            ->willReturn($this->businessBeerCategoryRepository)
            ->shouldBeCalled();

        $this->businessBeerCategoryRepository->find($this->command->id)->willReturn(null)->shouldBeCalled();

        $this->setExpectedException(BusinessBeerCategoryNotFoundException::class);

        $this->entityManager->flush()->shouldNotBeCalled();
        $this->recorder->record(Argument::any())->shouldNotBeCalled();

        $this->handler->handle($this->command);
    }
}
