<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\DeleteBusinessBeerCategoryCommand;
use AppBundle\Command\DeleteBusinessBeerCategoryCommandHandler;
use AppBundle\Entity\Business;
use AppBundle\Entity\BusinessBeerCategory;
use AppBundle\Event\BusinessBeerCategoryDeletedEvent;
use AppBundle\Exception\BusinessBeerCategoryInUseException;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Prophecy\Argument;
use SimpleBus\Message\Recorder\RecordsMessages;

class DeleteBusinessBeerCategoryCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var DeleteBusinessBeerCategoryCommandHandler */
    private $handler;

    /** @var mixed */
    private $recorder;

    /** @var mixed */
    private $entityManager;

    /** @var mixed */
    private $businessBeerCategoryRepository;

    /** @var DeleteBusinessBeerCategoryCommand */
    private $command;

    /** @var BusinessBeerCategory */
    private $category;

    public function setUp()
    {
        $this->recorder = $this->prophesize(RecordsMessages::class);
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->businessBeerCategoryRepository = $this->prophesize(ObjectRepository::class);

        $this->command = new DeleteBusinessBeerCategoryCommand();
        $this->command->id = 1;

        $this->category = new BusinessBeerCategory(
            new Business('name', 'address', 'phone', 'email'),
            'category'
        );

        /** @noinspection PhpParamsInspection */
        $this->handler = new DeleteBusinessBeerCategoryCommandHandler(
            $this->recorder->reveal(),
            $this->entityManager->reveal()
        );
    }

    public function testDeleteExists()
    {
        $this->entityManager
            ->getRepository('AppBundle:BusinessBeerCategory')
            ->willReturn($this->businessBeerCategoryRepository)
            ->shouldBeCalled();

        $this->businessBeerCategoryRepository->find($this->command->id)->willReturn($this->category)->shouldBeCalled();

        $this->entityManager->remove($this->category)->shouldBeCalled();
        $this->entityManager->flush()->shouldBeCalled();
        $this->recorder->record(new BusinessBeerCategoryDeletedEvent($this->category))->shouldBeCalled();

        $this->handler->handle($this->command);
    }

    public function testDeleteNotFound()
    {
        $this->entityManager
            ->getRepository('AppBundle:BusinessBeerCategory')
            ->willReturn($this->businessBeerCategoryRepository)
            ->shouldBeCalled();

        $this->businessBeerCategoryRepository->find($this->command->id)->willReturn(null)->shouldBeCalled();

        $this->entityManager->remove(Argument::any())->shouldNotBeCalled();
        $this->entityManager->flush()->shouldNotBeCalled();
        $this->recorder->record($this->category)->shouldNotBeCalled();

        $this->handler->handle($this->command);
    }

    public function testDeleteInUse()
    {
        $this->entityManager
            ->getRepository('AppBundle:BusinessBeerCategory')
            ->willReturn($this->businessBeerCategoryRepository)
            ->shouldBeCalled();

        $this->businessBeerCategoryRepository->find($this->command->id)->willReturn($this->category)->shouldBeCalled();

        $exception = $this->prophesize(ForeignKeyConstraintViolationException::class);

        $this->entityManager->remove($this->category)->shouldBeCalled();
        $this->entityManager->flush()->willThrow($exception->reveal())->shouldBeCalled();
        $this->recorder->record($this->category)->shouldNotBeCalled();

        $this->setExpectedException(BusinessBeerCategoryInUseException::class);

        $this->handler->handle($this->command);
    }
}
