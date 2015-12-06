<?php

namespace AppBundle\Controller\Business;

use AppBundle\Command\CreateBusinessBeerCategoryCommand;
use AppBundle\Command\DeleteBusinessBeerCategoryCommand;
use AppBundle\Command\EditBusinessBeerCategoryCommand;
use AppBundle\Entity\BusinessBeerCategory;
use AppBundle\Event\BusinessBeerCategoryCreatedEvent;
use AppBundle\Form\BusinessBeerCategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/business/beer-categories")
 */
class BeerCategoryController extends Controller
{
    /**
     * Lists all Beer category entities.
     *
     * @Route("/", name="business.beer-category.list")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction()
    {
        $business = $this->get('session.business')->getBusiness();

        $categories = $this->getDoctrine()
            ->getRepository('AppBundle:BusinessBeerCategory')
            ->findBy(['business' => $business]);

        return $this->render(':business/beer-category:index.html.twig', ['categories' => $categories]);
    }

    /**
     * Displays a form to create a new Business beer category entity.
     *
     * @Route("/new", name="business.beer-category.new")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $command = new CreateBusinessBeerCategoryCommand();
        $command->business = $this->get('session.business')->getBusiness();

        $form = $this->createForm(
            BusinessBeerCategoryType::class,
            $command,
            ['method' => 'POST']
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var BusinessBeerCategory $category */
            // Register a temporary event to catch the created business from the event raised
            $category = $this->get('app.event_promises')->delegate(
                function (BusinessBeerCategoryCreatedEvent $event) {
                    return $event->getCategory();
                }
            );

            $this->get('command_bus')->handle($form->getData());

            return $this->redirectToRoute('business.beer-category.show', ['id' => $category->getId()]);
        }

        return $this->render(':business/beer-category:new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Finds and displays a Business beer category entity.
     *
     * @Route("/{id}", name="business.beer-category.show")
     * @Method("GET")
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $category = $this->getDoctrine()->getRepository('AppBundle:BusinessBeerCategory')->find($id);

        // If the category is not found, show a 404
        if (!$category) {
            throw $this->createNotFoundException('Unable to find Business beer category entity.');
        }

        return $this->render(':business/beer-category:show.html.twig', ['entity' => $category]);
    }

    /**
     * Displays a form to edit an existing Business beer category entity.
     *
     * @Route("/{id}/edit", name="business.beer-category.edit")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, $id)
    {
        $category = $this->getDoctrine()->getRepository('AppBundle:BusinessBeerCategory')->find($id);

        // If the category is not found, show a 404
        if (!$category) {
            throw $this->createNotFoundException('Unable to find Business beer category entity.');
        }

        $form = $this->createForm(
            BusinessBeerCategoryType::class,
            $this->createEditBusinessBeerCategoryCommand($category),
            ['method' => 'PUT']
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('command_bus')->handle($form->getData());

            return $this->redirectToRoute('business.beer-category.show', ['id' => $id]);
        }

        return $this->render(
            ':business/beer-category:edit.html.twig',
            [
                'entity' => $category,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a Business beer category entity.
     *
     * @Route("/{id}", name="business.beer-category.delete")
     * @Method("DELETE")
     * @param $id
     * @return Response
     */
    public function deleteAction($id)
    {
        $command = new DeleteBusinessBeerCategoryCommand();
        $command->id = $id;

        $this->get('command_bus')->handle($command);

        return $this->redirectToRoute('business.beer-category.list');
    }

    /**
     * Create the command from a business beer category entity
     *
     * @param BusinessBeerCategory $entity
     * @return EditBusinessBeerCategoryCommand
     */
    private function createEditBusinessBeerCategoryCommand(BusinessBeerCategory $entity)
    {
        $command = new EditBusinessBeerCategoryCommand();

        $command->id = $entity->getId();
        $command->name = $entity->getName();

        return $command;
    }
}
