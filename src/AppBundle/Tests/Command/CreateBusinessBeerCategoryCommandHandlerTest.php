<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\Business\Beer\Category\CreateBusinessBeerCategoryCommand;
use AppBundle\Command\Business\Beer\Category\CreateBusinessBeerCategoryCommandHandler;
use AppBundle\Entity\Business;
use AppBundle\Entity\BusinessBeerCategory;
use AppBundle\Event\BusinessBeerCategoryCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Prophecy\Argument;
use SimpleBus\Message\Recorder\RecordsMessages;

class CreateBusinessBeerCategoryCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var CreateBusinessBeerCategoryCommandHandler */
    private $handler;

    /** @var mixed */
    private $recorder;

    /** @var mixed */
    private $entityManager;

    /** @var CreateBusinessBeerCategoryCommand */
    private $command;

    public function setUp()
    {
        $this->recorder = $this->prophesize(RecordsMessages::class);
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);

        $this->command = new CreateBusinessBeerCategoryCommand();
        $this->command->name = 'name';
        $this->command->business = new Business(
            'business-name',
            'business-address',
            'business-phone',
            'administrator-email'
        );

        /** @noinspection PhpParamsInspection */
        $this->handler = new CreateBusinessBeerCategoryCommandHandler(
            $this->recorder->reveal(),
            $this->entityManager->reveal()
        );
    }

    public function testSave()
    {
        // Check that the category match expected values
        $compareBusinessBeerCategory = function (BusinessBeerCategory $compareBusinessBeerCategory) {
            return $this->command->name == $compareBusinessBeerCategory->getName()
                && $this->command->business == $compareBusinessBeerCategory->getBusiness()
            ;
        };

        $this->entityManager->persist(Argument::that($compareBusinessBeerCategory))->shouldBeCalled();
        $this->entityManager->flush()->shouldBeCalled();

        $this->recorder->record(Argument::that(function (BusinessBeerCategoryCreatedEvent $event) use ($compareBusinessBeerCategory) {
            return $compareBusinessBeerCategory($event->getCategory());
        }))->shouldBeCalled();

        $this->handler->handle($this->command);
    }
}
