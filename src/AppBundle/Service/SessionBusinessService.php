<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * This class provides the business entity for the current session
 *
 * It can return the business entity only if the current request is made in the configured firewall.
 *
 * @package AppBundle\Service
 */
class SessionBusinessService
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /** @var string */
    private $businessFirewall;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * SessionBusinessService constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $em
     * @param string $businessFirewall
     */
    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $em, $businessFirewall)
    {
        $this->tokenStorage = $tokenStorage;
        $this->businessFirewall = $businessFirewall;
        $this->em = $em;
    }

    /**
     * Get the business for the request
     *
     * @return \AppBundle\Entity\Business|null
     */
    public function getBusiness()
    {
        $token = $this->tokenStorage->getToken();

        if (!$this->isFirewallCompatible($token)) {
            return null;
        }

        $user = $token->getUser();
        return $user
            ? $this->em->getRepository('AppBundle:Business')->findOneBy(['administratorUser' => $user])
            : null;
    }

    /**
     * Check that the request is made in the configured firewall
     *
     * @param $token
     * @return bool
     */
    private function isFirewallCompatible($token)
    {
        $firewallName = $token && method_exists($token, 'getProviderKey')
            ? $token->getProviderKey()
            : null;

        return $firewallName === $this->businessFirewall;
    }
}
