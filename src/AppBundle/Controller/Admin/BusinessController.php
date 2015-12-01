<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Command\CreateBusinessCommand;
use AppBundle\Command\DeleteBusinessCommand;
use AppBundle\Command\EditBusinessCommand;
use AppBundle\Event\BusinessCreatedEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Business;
use AppBundle\Form\BusinessType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Business controller.
 *
 * @Route("/admin/businesses")
 */
class BusinessController extends Controller
{
    /** @const The number of results displayed per page */
    const RESULTS_PER_PAGE = 20;

    /**
     * Lists all Business entities.
     *
     * @Route("/", name="admin.business.list")
     * @Method("GET")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $pagination = $this->get('knp_paginator')->paginate(
            $this->getDoctrine()->getRepository('AppBundle:Business')->findAllQuery(),
            $request->query->getInt('page', 1),
            self::RESULTS_PER_PAGE
        );

        // Get the search value to be able to display it again on the page
        $filterValue = $request->query->get('filterValue', '');

        return $this->render(
            ':admin/business:index.html.twig',
            [
                'pagination' => $pagination,
                'filterValue' => $filterValue
            ]
        );
    }

    /**
     * Displays a form to create a new Business entity.
     *
     * @Route("/new", name="admin.business.new")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(BusinessType::class, new CreateBusinessCommand(), ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Business $business */
            // Register a temporary event to catch the created business from the event raised
            $business = $this->get('app.event_promises')->delegate(
                function (BusinessCreatedEvent $event) {
                    return $event->getBusiness();
                }
            );

            $this->get('command_bus')->handle($form->getData());

            return $this->redirectToRoute('admin.business.show', ['id' => $business->getId()]);
        }

        return $this->render(':admin/business:new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Finds and displays a Business entity.
     *
     * @Route("/{id}", name="admin.business.show")
     * @Method("GET")
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $business = $this->getDoctrine()->getRepository('AppBundle:Business')->find($id);

        // If the business is not found, show a 404
        if (!$business) {
            throw $this->createNotFoundException('Unable to find Business entity.');
        }

        return $this->render(':admin/business:show.html.twig', ['entity' => $business]);
    }

    /**
     * Displays a form to edit an existing Business entity.
     *
     * @Route("/{id}/edit", name="admin.business.edit")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, $id)
    {
        $business = $this->getDoctrine()->getRepository('AppBundle:Business')->find($id);

        // If the business is not found, show a 404
        if (!$business) {
            throw $this->createNotFoundException('Unable to find Business entity.');
        }

        $form = $this->createForm(
            BusinessType::class,
            $this->createEditBusinessCommand($business),
            ['method' => 'PUT']
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('command_bus')->handle($form->getData());

            return $this->redirectToRoute('admin.business.show', ['id' => $id]);
        }

        return $this->render(
            ':admin/business:edit.html.twig',
            [
                'entity' => $business,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a Business entity.
     *
     * @Route("/{id}", name="admin.business.delete")
     * @Method("DELETE")
     * @param $id
     * @return Response
     */
    public function deleteAction($id)
    {
        $command = new DeleteBusinessCommand();
        $command->id = $id;

        $this->get('command_bus')->handle($command);

        return $this->redirectToRoute('admin.business.list');
    }

    /**
     * Create the command from a business entity
     *
     * @param Business $entity
     * @return EditBusinessCommand
     */
    private function createEditBusinessCommand(Business $entity)
    {
        $command = new EditBusinessCommand();

        $command->id = $entity->getId();
        $command->name = $entity->getName();
        $command->address = $entity->getAddress();
        $command->phone = $entity->getPhone();
        $command->administratorEmail = $entity->getAdministratorUser()->getEmail();

        return $command;
    }
}
