<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $search = $request->query->get('search', '');

        if ($search) {
            $businesses = $this->getDoctrine()->getRepository('AppBundle:Business')
                ->createQueryBuilder('b')
                ->where('b.address LIKE :address')
                ->setParameter(':address', '%'.$search.'%')
                ->getQuery()
                ->execute();
            ;
        } else {
            $businesses = $this->getDoctrine()->getRepository('AppBundle:Business')->findAll();
        }

        return $this->render(':home:index.html.twig', ['businesses' => $businesses, 'search' => $search]);
    }

    /**
     * @Route("/beers/{business_id}", name="user.beers")
     *
     * @param $business_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function businessAction($business_id)
    {
        $business = $this->getDoctrine()->getRepository('AppBundle:Business')->find($business_id);
        $beers = $this->getDoctrine()->getRepository('AppBundle:BusinessBeer')->findBy(['business' => $business]);

        return $this->render(':home:business.html.twig', ['business' => $business, 'beers' => $beers]);
    }
}
