<?php

namespace AppBundle\Command;

use AppBundle\Entity\Business;
use AppBundle\Event\BusinessUpdatedEvent;
use AppBundle\Exception\BusinessNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use SimpleBus\Message\Recorder\RecordsMessages;

class EditBusinessCommandHandler
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var RecordsMessages */
    private $recorder;

    public function __construct(RecordsMessages $recorder, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->recorder = $recorder;
    }

    public function handle(EditBusinessCommand $command)
    {
        $business = $this->em->getRepository('AppBundle:Business')->find($command->id);

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
