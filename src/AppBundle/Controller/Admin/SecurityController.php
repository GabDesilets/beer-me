<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Administration controller controller
 *
 * @Route("/admin")
 */
class SecurityController extends Controller
{
    /**
     * Display the administration login form
     *
     * @Route("/login", name="admin.login")
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render(
            ':admin:login.html.twig',
            [
                'lastUsername' => $authenticationUtils->getLastUsername(),
                'error'         => $authenticationUtils->getLastAuthenticationError(),
            ]
        );
    }
}
