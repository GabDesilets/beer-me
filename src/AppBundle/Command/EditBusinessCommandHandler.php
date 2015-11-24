<?php

namespace AppBundle\Command;

use AppBundle\Entity\Business;
use AppBundle\Event\BusinessUpdatedEvent;
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
        $business = $this->em->getRepository('AppBundle:Business')->find($command->getId());

        $business->setName($command->getName());
        $business->setPhone($command->getPhone());
        $business->setAddress($command->getAddress());

        $administrator = $business->getAdministratorUser();
        $administrator->setEmail($command->getAdministratorEmail());

        $this->em->flush();

        $this->recorder->record(new BusinessUpdatedEvent($business));
    }
}
