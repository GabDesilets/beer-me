<?php

namespace AppBundle\Command\BusinessRequest;

use AppBundle\Event\BusinessRequest\BusinessRequestDeletedEvent;
use Doctrine\ORM\EntityManagerInterface;
use SimpleBus\Message\Recorder\RecordsMessages;

/**
 * Handler for the business deletion command
 *
 * This handler delete the business from the database and raise a BusinessDeletedEvent
 */
class DeleteBusinessRequestCommandHandler
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
     * @param DeleteBusinessRequestCommand $command
     */
    public function handle(DeleteBusinessRequestCommand $command)
    {
        $businessRequest = $this->em->getRepository('AppBundle:BusinessRequest')->find($command->id);

        // If the business is not found, we do nothing.  No error or exception is raised.
        if ($businessRequest) {
            $this->em->remove($businessRequest);
            $this->em->flush();

            $this->recorder->record(new BusinessRequestDeletedEvent($businessRequest));
        }
    }
}
