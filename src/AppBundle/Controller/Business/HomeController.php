<?php

namespace AppBundle\Controller\Business;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/business")
 */
class HomeController extends Controller
{
    /**
     * @Route("/", name="business.home")
     */
    public function homeAction()
    {
        return new Response('<body>business</body>');
    }
}
