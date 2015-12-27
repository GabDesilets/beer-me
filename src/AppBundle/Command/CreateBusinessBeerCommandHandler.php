<?php

namespace AppBundle\Command;

use AppBundle\Entity\BusinessBeer;
use AppBundle\Event\BusinessBeerCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use SimpleBus\Message\Recorder\RecordsMessages;

/**
 * Handler for the business beer creation command
 *
 * This handler adds the business beer to the database and raise a BusinessBeerCreatedEvent
 */
class CreateBusinessBeerCommandHandler
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var RecordsMessages */
    private $recorder;

    /**
     * CreateBusinessBeerCommandHandler constructor.
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
     * @param CreateBusinessBeerCommand $command
     */
    public function handle(CreateBusinessBeerCommand $command)
    {
        $beer = new BusinessBeer(
            $command->category,
            $command->name,
            $command->notes
        );

        // Here we make the assumption that the values where validated for uniqueness and everything will go fine.
        // If this is not the case, the business will throw exceptions.  Those are not handled here.
        $this->em->persist($beer);
        $this->em->flush();

        $this->recorder->record(new BusinessBeerCreatedEvent($beer));
    }
}
