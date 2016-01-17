<?php

namespace AppBundle\Command\Business\Beer;

use AppBundle\Event\BusinessBeerUpdatedEvent;
use AppBundle\Exception\BusinessBeerNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use SimpleBus\Message\Recorder\RecordsMessages;

/**
 * Handler for the business beer edition command
 *
 * This handler edit an existing business beer and raise a BusinessBeerUpdatedEvent
 */
class EditBusinessBeerCommandHandler
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var RecordsMessages */
    private $recorder;

    /**
     * EditBusinessBeerCommandHandler constructor.
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
     * @param EditBusinessBeerCommand $command
     * @throws BusinessBeerNotFoundException
     */
    public function handle(EditBusinessBeerCommand $command)
    {
        $beer = $this->em->getRepository('AppBundle:BusinessBeer')->find($command->id);

        // If we try to edit an non-existing beer, we must throw an exception to inform the user
        if (null === $beer) {
            throw new BusinessBeerNotFoundException();
        }

        $beer->setCategory($command->category);
        $beer->setName($command->name);
        $beer->setNotes($command->notes);

        $this->em->flush();

        $this->recorder->record(new BusinessBeerUpdatedEvent($beer));
    }
}
