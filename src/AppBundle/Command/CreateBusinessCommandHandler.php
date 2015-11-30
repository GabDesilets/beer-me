<?php

namespace AppBundle\Command;

use AppBundle\Entity\Business;
use AppBundle\Event\BusinessCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use SimpleBus\Message\Recorder\RecordsMessages;

/**
 * Handler for the business creation command
 *
 * This handler adds the business to the database and raise a BusinessCreatedEvent
 */
class CreateBusinessCommandHandler
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var RecordsMessages */
    private $recorder;

    /**
     * CreateBusinessCommandHandler constructor.
     *
     * @param RecordsMessages $recorder
     * @param EntityManagerInterface $em
     */
    public function __construct(RecordsMessages $recorder, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->recorder = $recorder;
    }

    /**
     * Handle the command
     *
     * @param CreateBusinessCommand $command
     */
    public function handle(CreateBusinessCommand $command)
    {
        $business = new Business(
            $command->name,
            $command->address,
            $command->phone,
            $command->administratorEmail
        );

        // Here we make the assumption that the values where validated for uniqueness and everything will go fine.
        // If this is not the case, the business will throw exceptions.  Those are not handled here.
        $this->em->persist($business);
        $this->em->flush();

        $this->recorder->record(new BusinessCreatedEvent($business));
    }
}
