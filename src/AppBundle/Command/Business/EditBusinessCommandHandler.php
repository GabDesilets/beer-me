<?php

namespace AppBundle\Command\Business;

use AppBundle\Entity\Business;
use AppBundle\Event\BusinessUpdatedEvent;
use AppBundle\Exception\BusinessNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use SimpleBus\Message\Recorder\RecordsMessages;

/**
 * Handler for the business edition command
 *
 * This handler edit an existing business and raise a BusinessUpdatedEvent
 */
class EditBusinessCommandHandler
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var RecordsMessages */
    private $recorder;

    /**
     * EditBusinessCommandHandler constructor.
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
     * @param EditBusinessCommand $command
     * @throws BusinessNotFoundException
     */
    public function handle(EditBusinessCommand $command)
    {
        $business = $this->em->getRepository('AppBundle:Business')->find($command->id);

        // If we try to edit an non-existing business, we must throw an exception to inform the user
        if (null === $business) {
            throw new BusinessNotFoundException();
        }

        $business->setName($command->name);
        $business->setPhone($command->phone);
        $business->setAddress($command->address);

        $administrator = $business->getAdministratorUser();
        $administrator->setEmail($command->administratorEmail);

        $this->em->flush();

        $this->recorder->record(new BusinessUpdatedEvent($business));
    }
}
