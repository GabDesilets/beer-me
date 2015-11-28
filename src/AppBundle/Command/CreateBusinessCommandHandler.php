<?php

namespace AppBundle\Command;

use AppBundle\Entity\Business;
use AppBundle\Event\BusinessCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use SimpleBus\Message\Recorder\RecordsMessages;

class CreateBusinessCommandHandler
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

    public function handle(CreateBusinessCommand $command)
    {
        $business = new Business(
            $command->name,
            $command->address,
            $command->phone,
            $command->administratorEmail
        );

        $this->em->persist($business);
        $this->em->flush();

        $this->recorder->record(new BusinessCreatedEvent($business));
    }
}
