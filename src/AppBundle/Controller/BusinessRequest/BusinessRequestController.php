<?php

namespace AppBundle\Controller\BusinessRequest;

use AppBundle\Command\AcceptBusinessRequestCommand;
use AppBundle\Command\CreateBusinessRequestCommand;
use AppBundle\Command\DeleteBusinessRequestCommand;
use AppBundle\Form\BusinessRequestType;
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
    /** @const The number of results displayed per page */
    const RESULTS_PER_PAGE = 20;

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
     * @Route("/business-request-thank", name="business.request.thank")
     * @return Response
     */
    public function thankAction()
    {
        return $this->render(':business_request:thanks.html.twig');
    }

    /**
     * @Route("/business-requests-count", name="business.requests.count")
     * @return Response
     */
    public function countAction()
    {
        return $this->render(':business_request:active_request_menu_side.html.twig', array(
            'businessRequestCount' => count(
                $this->getDoctrine()
                ->getRepository('AppBundle:BusinessRequest')
                    ->findAll()
            ),
        ));
    }

    /**
     * Lists all Business requests.
     *
     * @Route("/admin/business-request-list", name="admin.business.request.list")
     * @Method("GET")
     *
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $pagination = $this->get('knp_paginator')->paginate(
            $this->getDoctrine()->getRepository('AppBundle:BusinessRequest')->findAll(),
            $request->query->getInt('page', 1),
            self::RESULTS_PER_PAGE
        );

        // Get the search value to be able to display it again on the page
        $filterValue = $request->query->get('filterValue', '');

        return $this->render(
            ':business_request:list.html.twig',
            [
                'pagination' => $pagination,
                'filterValue' => $filterValue
            ]
        );
    }

    /**
     * Deletes a Business Request entity.
     *
     * @Route("/business-request/{id}", name="admin.business.request.delete")
     * @Method("DELETE")
     * @param $id
     * @return Response
     */
    public function deleteAction($id)
    {
        $command = new DeleteBusinessRequestCommand();
        $command->id = $id;

        $this->get('command_bus')->handle($command);

        return $this->redirectToRoute('admin.business.request.list');
    }

    /**
     * Accept a Business Request entity.
     *
     * @Route("/business-request/{id}/accept", name="admin.business.request.accept")
     * @Method("POST")
     * @param $id
     * @return Response
     */
    public function acceptAction($id)
    {
        $command = new AcceptBusinessRequestCommand();
        $command->id = $id;

        $this->get('command_bus')->handle($command);

        return $this->redirectToRoute('admin.business.request.list');
    }
}
