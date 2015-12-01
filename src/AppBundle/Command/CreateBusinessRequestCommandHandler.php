<?php

namespace AppBundle\Command;

use AppBundle\Entity\BusinessRequest;
use AppBundle\Event\BusinessRequestCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use SimpleBus\Message\Recorder\RecordsMessages;

class CreateBusinessRequestCommandHandler
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

    /**
     * @param CreateBusinessRequestCommand $command
     */
    public function handle(CreateBusinessRequestCommand $command)
    {
        $businessRequest = new BusinessRequest(
            $command->getFirstName(),
            $command->getLastName(),
            $command->getBusinessName(),
            $command->getEmail(),
            $command->getAddress(),
            $command->getZipCode(),
            $command->getCountry(),
            $command->getProvince(),
            $command->getCity(),
            $command->getPhoneOne(),
            $command->getPhoneTwo(),
            $command->getPhoneThree()
        );

        $businessRequest->setPhone();

        $this->em->persist($businessRequest);
        $this->em->flush();

        $this->recorder->record(new BusinessRequestCreatedEvent($businessRequest));
    }
}
