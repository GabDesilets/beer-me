<?php

namespace AppBundle\Command;

use AppBundle\Event\BusinessBeerCategoryUpdatedEvent;
use AppBundle\Exception\BusinessBeerCategoryNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use SimpleBus\Message\Recorder\RecordsMessages;

/**
 * Handler for the business beer category edition command
 *
 * This handler edit an existing business beer category and raise a BusinessBeerCategoryUpdatedEvent
 */
class EditBusinessBeerCategoryCommandHandler
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var RecordsMessages */
    private $recorder;

    /**
     * EditBusinessBeerCategoryCommandHandler constructor.
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
     * @param EditBusinessBeerCategoryCommand $command
     * @throws BusinessBeerCategoryNotFoundException
     */
    public function handle(EditBusinessBeerCategoryCommand $command)
    {
        $category = $this->em->getRepository('AppBundle:BusinessBeerCategory')->find($command->id);

        // If we try to edit an non-existing category, we must throw an exception to inform the user
        if (null === $category) {
            throw new BusinessBeerCategoryNotFoundException();
        }

        $category->setName($command->name);

        $this->em->flush();

        $this->recorder->record(new BusinessBeerCategoryUpdatedEvent($category));
    }
}
