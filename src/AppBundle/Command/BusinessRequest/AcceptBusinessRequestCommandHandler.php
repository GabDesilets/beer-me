<?php

namespace AppBundle\Command\BusinessRequest;

use AppBundle\Command\CreateBusinessCommand;
use AppBundle\Event\BusinessRequest\BusinessRequestAcceptedEvent;
use Doctrine\ORM\EntityManagerInterface;
use SimpleBus\Message\Bus\MessageBus;
use SimpleBus\Message\Recorder\RecordsMessages;

/**
 * Handler for the business deletion command
 *
 * This handler accept the business request from the database and raise a BusinessAcceptEvent
 */
class AcceptBusinessRequestCommandHandler
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var RecordsMessages */
    private $recorder;

    /** @var MessageBus  */
    private $commandBus;

    /**
     * AcceptBusinessCommandHandler constructor.
     *
     * @param RecordsMessages $recorder
     * @param EntityManagerInterface $em
     * @param MessageBus $commandBus
     */
    public function __construct(RecordsMessages $recorder, EntityManagerInterface $em, MessageBus $commandBus)
    {
        $this->em = $em;
        $this->recorder = $recorder;
        $this->commandBus = $commandBus;
    }

    /**
     * Handle the command
     *
     * @param AcceptBusinessRequestCommand $command
     */
    public function handle(AcceptBusinessRequestCommand $command)
    {
        $businessRequest = $this->em->getRepository('AppBundle:BusinessRequest')->find($command->id);
        // If the business is not found, we do nothing.  No error or exception is raised.
        if ($businessRequest) {
            $createBusinessCommand = new CreateBusinessCommand();
            $createBusinessCommand->address = $businessRequest->getAddress();
            $createBusinessCommand->administratorEmail = $businessRequest->getEmail();
            $createBusinessCommand->name = $businessRequest->getBusinessName();
            $createBusinessCommand->phone = $businessRequest->getPhone();

            $deleteBusinessCommand = new DeleteBusinessRequestCommand();
            $deleteBusinessCommand->id = $businessRequest->getId();

            $this->commandBus->handle($createBusinessCommand);
            $this->commandBus->handle($deleteBusinessCommand);

            $this->recorder->record(new BusinessRequestAcceptedEvent($businessRequest));
        }
    }
}
