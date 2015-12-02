<?php

namespace AppBundle\Controller\BusinessRequest;

use AppBundle\Command\CreateBusinessRequestCommand;
use AppBundle\Event\BusinessRequestCreatedEvent;
use AppBundle\Form\BusinessRequestType;
use AppBundle\Form\RequestAccessType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/")
 */
class BusinessRequestController extends Controller
{
    /**
     * @Route("/join-us", name="join.us")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(BusinessRequestType::class, new CreateBusinessRequestCommand(), ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('command_bus')->handle($form->getData());
            return $this->redirectToRoute('business.request.thank');
        }

        return $this->render(
            ':business_request:public_business_request.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/thank", name="business.request.thank")
     * @return Response
     */
    public function thankAction()
    {
        return new Response("Thanks bud!");
    }
}
