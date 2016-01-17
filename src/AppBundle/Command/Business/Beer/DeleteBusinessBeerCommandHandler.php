<?php

namespace AppBundle\Command\Business\Beer;

use AppBundle\Event\BusinessBeerDeletedEvent;
use Doctrine\ORM\EntityManagerInterface;
use SimpleBus\Message\Recorder\RecordsMessages;

/**
 * Handler for the business beer deletion command
 *
 * This handler delete the business beer from the database and raise a BusinessBeerDeletedEvent
 */
class DeleteBusinessBeerCommandHandler
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var RecordsMessages */
    private $recorder;

    /**
     * DeleteBusinessBeerCommandHandler constructor.
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
     * @param DeleteBusinessBeerCommand $command
     */
    public function handle(DeleteBusinessBeerCommand $command)
    {
        $beer = $this->em->getRepository('AppBundle:BusinessBeer')->find($command->id);

        // If the beer is not found, we do nothing.  No error or exception is raised.
        if ($beer) {
            $this->em->remove($beer);
            $this->em->flush();

            $this->recorder->record(new BusinessBeerDeletedEvent($beer));
        }
    }
}
