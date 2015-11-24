<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin")
 */
class SecurityController extends Controller
{
    /**
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
