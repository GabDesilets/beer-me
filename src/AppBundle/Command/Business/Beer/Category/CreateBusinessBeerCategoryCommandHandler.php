<?php

namespace AppBundle\Command\Business\Beer\Category;

use AppBundle\Entity\BusinessBeerCategory;
use AppBundle\Event\BusinessBeerCategoryCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use SimpleBus\Message\Recorder\RecordsMessages;

/**
 * Handler for the business beer category creation command
 *
 * This handler adds the business beer category to the database and raise a BusinessBeerCategoryCreatedEvent
 */
class CreateBusinessBeerCategoryCommandHandler
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var RecordsMessages */
    private $recorder;

    /**
     * CreateBusinessBeerCategoryCommandHandler constructor.
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
     * @param CreateBusinessBeerCategoryCommand $command
     */
    public function handle(CreateBusinessBeerCategoryCommand $command)
    {
        $category = new BusinessBeerCategory(
            $command->business,
            $command->name
        );

        // Here we make the assumption that the values where validated for uniqueness and everything will go fine.
        // If this is not the case, the business will throw exceptions.  Those are not handled here.
        $this->em->persist($category);
        $this->em->flush();

        $this->recorder->record(new BusinessBeerCategoryCreatedEvent($category));
    }
}
