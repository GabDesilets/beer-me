<?php

namespace AppBundle\Command;

use AppBundle\Event\BusinessDeletedEvent;
use Doctrine\ORM\EntityManagerInterface;
use SimpleBus\Message\Recorder\RecordsMessages;

/**
 * Handler for the business deletion command
 *
 * This handler delete the business from the database and raise a BusinessDeletedEvent
 */
class DeleteBusinessCommandHandler
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var RecordsMessages */
    private $recorder;

    /**
     * DeleteBusinessCommandHandler constructor.
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
     * @param DeleteBusinessCommand $command
     */
    public function handle(DeleteBusinessCommand $command)
    {
        $business = $this->em->getRepository('AppBundle:Business')->find($command->id);

        // If the business is not found, we do nothing.  No error or exception is raised.
        if ($business) {
            $this->em->remove($business);
            $this->em->flush();

            $this->recorder->record(new BusinessDeletedEvent($business));
        }
    }
}
