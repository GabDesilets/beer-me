<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin")
 */
class HomeController extends Controller
{
    /**
     * @Route("/", name="admin.homepage")
     */
    public function homeAction()
    {
        return $this->render(':admin:layout.html.twig');
    }
}
