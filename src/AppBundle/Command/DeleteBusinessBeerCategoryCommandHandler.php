<?php

namespace AppBundle\Command;

use AppBundle\Event\BusinessBeerCategoryDeletedEvent;
use Doctrine\ORM\EntityManagerInterface;
use SimpleBus\Message\Recorder\RecordsMessages;

/**
 * Handler for the business beer category deletion command
 *
 * This handler delete the business beer category from the database and raise a BusinessBeerCategoryDeletedEvent
 */
class DeleteBusinessBeerCategoryCommandHandler
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var RecordsMessages */
    private $recorder;

    /**
     * DeleteBusinessBeerCategoryCommandHandler constructor.
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
     * @param DeleteBusinessBeerCategoryCommand $command
     */
    public function handle(DeleteBusinessBeerCategoryCommand $command)
    {
        $category = $this->em->getRepository('AppBundle:BusinessBeerCategory')->find($command->id);

        // If the category is not found, we do nothing.  No error or exception is raised.
        if ($category) {
            $this->em->remove($category);
            $this->em->flush();

            $this->recorder->record(new BusinessBeerCategoryDeletedEvent($category));
        }
    }
}
