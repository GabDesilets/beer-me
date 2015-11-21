<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Command\CreateBusinessCommand;
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
    /**
     * Lists all Business entities.
     *
     * @Route("/", name="admin.business.list")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Business')->findAll();

        return $this->render(':admin/business:index.html.twig', ['entities' => $entities]);
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

            /** @var Business $entity */
            $entity = $this->get('app.event_promises')->delegate(
                function(BusinessCreatedEvent $event) {
                    return $event->getEntity();
                }
            );

            $this->get('command_bus')->handle($form->getData());
            return $this->redirectToRoute('admin.business.show', ['id' => $entity->getId()]);
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
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Business')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Business entity.');
        }

        return $this->render(':admin/business:show.html.twig', ['entity' => $entity]);
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
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Business')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Business entity.');
        }

        $form = $this->createForm(BusinessType::class, $this->createEditBusinessCommand($entity), ['method' => 'PUT']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('command_bus')->handle($form->getData());
            return $this->redirectToRoute('admin.business.show', ['id' => $id]);
        }

        return $this->render(
            ':admin/business:edit.html.twig',
            [
                'entity' => $entity,
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
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Business')->find($id);

        if ($entity) {
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirectToRoute('admin.business.list');
    }

    /**
     * @param $entity
     * @return EditBusinessCommand
     */
    private function createEditBusinessCommand(Business $entity)
    {
        $command = new EditBusinessCommand();

        $command->setId($entity->getId());
        $command->setName($entity->getName());
        $command->setAddress($entity->getAddress());
        $command->setPhone($entity->getPhone());
        $command->setAdministratorEmail($entity->getAdministratorUser()->getEmail());
        return $command;
    }
}
