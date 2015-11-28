<?php

namespace AppBundle\Command;

use AppBundle\Event\BusinessDeletedEvent;
use Doctrine\ORM\EntityManagerInterface;
use SimpleBus\Message\Recorder\RecordsMessages;

class DeleteBusinessCommandHandler
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

    public function handle(DeleteBusinessCommand $command)
    {
        $entity = $this->em->getRepository('AppBundle:Business')->find($command->id);

        if ($entity) {
            $this->em->remove($entity);
            $this->em->flush();

            $this->recorder->record(new BusinessDeletedEvent($entity));
        }
    }
}
