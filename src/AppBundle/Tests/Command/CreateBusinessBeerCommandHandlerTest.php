<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\CreateBusinessBeerCommand;
use AppBundle\Command\CreateBusinessBeerCommandHandler;
use AppBundle\Entity\Business;
use AppBundle\Entity\BusinessBeer;
use AppBundle\Entity\BusinessBeerCategory;
use AppBundle\Event\BusinessBeerCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Prophecy\Argument;
use SimpleBus\Message\Recorder\RecordsMessages;

class CreateBusinessBeerCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var CreateBusinessBeerCommandHandler */
    private $handler;

    /** @var mixed */
    private $recorder;

    /** @var mixed */
    private $entityManager;

    /** @var CreateBusinessBeerCommand */
    private $command;

    public function setUp()
    {
        $this->recorder = $this->prophesize(RecordsMessages::class);
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);

        $this->command = new CreateBusinessBeerCommand();
        $this->command->name = 'name';
        $this->command->category = new BusinessBeerCategory(
            new Business(
                'business-name',
                'business-address',
                'business-phone',
                'administrator-email'
            ),
            'category'
        );

        /** @noinspection PhpParamsInspection */
        $this->handler = new CreateBusinessBeerCommandHandler(
            $this->recorder->reveal(),
            $this->entityManager->reveal()
        );
    }

    public function testSave()
    {
        // Check that the beer match expected values
        $compareBusinessBeer = function (BusinessBeer $compareBusinessBeer) {
            return $this->command->name == $compareBusinessBeer->getName()
                && $this->command->category == $compareBusinessBeer->getCategory()
            ;
        };

        $this->entityManager->persist(Argument::that($compareBusinessBeer))->shouldBeCalled();
        $this->entityManager->flush()->shouldBeCalled();

        $this->recorder->record(Argument::that(function (BusinessBeerCreatedEvent $event) use ($compareBusinessBeer) {
            return $compareBusinessBeer($event->getBeer());
        }))->shouldBeCalled();

        $this->handler->handle($this->command);
    }
}
