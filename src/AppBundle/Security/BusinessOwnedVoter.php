<?php

namespace AppBundle\Security;

use AppBundle\Entity\BusinessOwnedInterface;
use AppBundle\Service\SessionBusinessService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BusinessOwnedVoter extends Voter
{
    const EDIT = 'edit';

    /**
     * @var SessionBusinessService
     */
    private $sessionBusiness;

    public function __construct(SessionBusinessService $sessionBusiness)
    {
        $this->sessionBusiness = $sessionBusiness;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::EDIT])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof BusinessOwnedInterface) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if (!$subject instanceof BusinessOwnedInterface) {
            return false;
        }

        return $subject->getBusiness() === $this->sessionBusiness->getBusiness();
    }
}
