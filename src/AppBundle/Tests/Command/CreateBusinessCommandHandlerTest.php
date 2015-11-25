<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\CreateBusinessCommand;
use AppBundle\Command\CreateBusinessCommandHandler;
use AppBundle\Entity\Business;
use AppBundle\Event\BusinessCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Prophecy\Argument;
use SimpleBus\Message\Recorder\RecordsMessages;

class CreateBusinessCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var CreateBusinessCommandHandler */
    private $handler;

    /** @var mixed */
    private $recorder;

    /** @var mixed */
    private $entityManager;

    /** @var CreateBusinessCommand */
    private $command;

    public function setUp()
    {
        $this->recorder = $this->prophesize(RecordsMessages::class);
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);

        $this->command = new CreateBusinessCommand();
        $this->command->setPhone('phone');
        $this->command->setName('name');
        $this->command->setAddress('address');
        $this->command->setAdministratorEmail('email');

        /** @noinspection PhpParamsInspection */
        $this->handler = new CreateBusinessCommandHandler(
            $this->recorder->reveal(),
            $this->entityManager->reveal()
        );
    }

    public function testSave()
    {
        // Check that the business match expected values
        $compareBusiness = function(Business $compareBusiness) {
            return $this->command->getAdministratorEmail() == $compareBusiness->getAdministratorUser()->getEmail()
                && $this->command->getName() == $compareBusiness->getName()
                && $this->command->getAddress() == $compareBusiness->getAddress()
                && $this->command->getPhone() == $compareBusiness->getPhone()
            ;
        };

        $this->entityManager->persist(Argument::that($compareBusiness))->shouldBeCalled();
        $this->entityManager->flush()->shouldBeCalled();

        $this->recorder->record(Argument::that(function(BusinessCreatedEvent $event) use ($compareBusiness) {
            return $compareBusiness($event->getEntity());
        }))->shouldBeCalled();


        $this->handler->handle($this->command);
    }
}
