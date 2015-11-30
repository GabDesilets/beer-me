<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Administration dashboard controller
 *
 * @Route("/admin")
 */
class HomeController extends Controller
{
    /**
     * Display the administration dashboard
     *
     * @Route("/", name="admin.homepage")
     */
    public function homeAction()
    {
        return $this->render(':admin:layout.html.twig');
    }
}
