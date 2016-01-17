<?php

namespace AppBundle\Command\Business\Beer\Category;

use AppBundle\Event\BusinessBeerCategoryDeletedEvent;
use AppBundle\Exception\BusinessBeerCategoryInUseException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
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
     * @throws BusinessBeerCategoryInUseException
     */
    public function handle(DeleteBusinessBeerCategoryCommand $command)
    {
        $category = $this->em->getRepository('AppBundle:BusinessBeerCategory')->find($command->id);

        // If the category is not found, we do nothing.  No error or exception is raised.
        if ($category) {
            try {
                $this->em->remove($category);
                $this->em->flush();

                $this->recorder->record(new BusinessBeerCategoryDeletedEvent($category));
            } catch (ForeignKeyConstraintViolationException $e) {
                throw new BusinessBeerCategoryInUseException('', 0, $e);
            }
        }
    }
}
