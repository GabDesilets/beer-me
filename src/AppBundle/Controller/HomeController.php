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
     * @Route("/beers/{business_id}-{business_slug}", name="user.beers")
     * @Route("/beers/{business_id}", defaults={"business_slug" = ""})
     *
     * @param $business_id
     * @param $business_slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function businessAction($business_id, $business_slug)
    {
        $business = $this->getDoctrine()->getRepository('AppBundle:Business')->find($business_id);
        $beers = $this->getDoctrine()->getRepository('AppBundle:BusinessBeer')->findBy(['business' => $business]);

        // Redirect to the correct slug if an incorrect one is used
        $realSlug = $this->get('slugify')->slugify($business->getName());
        if ($business && $realSlug !== $business_slug) {
            return $this->redirectToRoute('user.beers', ['business_id' => $business_id, 'business_slug' => $realSlug]);
        }

        return $this->render(':home:business.html.twig', ['business' => $business, 'beers' => $beers]);
    }
}
