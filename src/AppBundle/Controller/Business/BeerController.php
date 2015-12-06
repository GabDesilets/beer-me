<?php

namespace AppBundle\Controller\Business;

use AppBundle\Command\CreateBusinessBeerCommand;
use AppBundle\Command\DeleteBusinessBeerCommand;
use AppBundle\Command\EditBusinessBeerCommand;
use AppBundle\Entity\BusinessBeer;
use AppBundle\Event\BusinessBeerCreatedEvent;
use AppBundle\Form\BusinessBeerCategoryType;
use AppBundle\Form\BusinessBeerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/business/beers")
 */
class BeerController extends Controller
{
    /**
     * Lists all Beer entities.
     *
     * @Route("/", name="business.beer.list")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction()
    {
        $business = $this->get('session.business')->getBusiness();

        $beers = $this->getDoctrine()
            ->getRepository('AppBundle:BusinessBeer')
            ->findBy(['business' => $business]);

        return $this->render(':business/beer:index.html.twig', ['beers' => $beers]);
    }

    /**
     * Displays a form to create a new Business beer entity.
     *
     * @Route("/new", name="business.beer.new")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(
            BusinessBeerType::class,
            new CreateBusinessBeerCommand(),
            ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var BusinessBeer $beer */
            // Register a temporary event to catch the created business from the event raised
            $beer = $this->get('app.event_promises')->delegate(
                function (BusinessBeerCreatedEvent $event) {
                    return $event->getBeer();
                }
            );

            $this->get('command_bus')->handle($form->getData());

            return $this->redirectToRoute('business.beer.show', ['id' => $beer->getId()]);
        }

        return $this->render(':business/beer:new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Finds and displays a Business beer entity.
     *
     * @Route("/{id}", name="business.beer.show")
     * @Method("GET")
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $beer = $this->getDoctrine()->getRepository('AppBundle:BusinessBeer')->find($id);

        // If the beer is not found, show a 404
        if (!$beer) {
            throw $this->createNotFoundException('Unable to find Business beer entity.');
        }

        return $this->render(':business/beer:show.html.twig', ['entity' => $beer]);
    }

    /**
     * Displays a form to edit an existing Business beer entity.
     *
     * @Route("/{id}/edit", name="business.beer.edit")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, $id)
    {
        $beer = $this->getDoctrine()->getRepository('AppBundle:BusinessBeer')->find($id);

        // If the beer is not found, show a 404
        if (!$beer) {
            throw $this->createNotFoundException('Unable to find Business beer entity.');
        }

        $form = $this->createForm(
            BusinessBeerType::class,
            $this->createEditBusinessBeerCommand($beer),
            ['method' => 'PUT']
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('command_bus')->handle($form->getData());

            return $this->redirectToRoute('business.beer.show', ['id' => $id]);
        }

        return $this->render(
            ':business/beer:edit.html.twig',
            [
                'entity' => $beer,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a Business beer entity.
     *
     * @Route("/{id}", name="business.beer.delete")
     * @Method("DELETE")
     * @param $id
     * @return Response
     */
    public function deleteAction($id)
    {
        $command = new DeleteBusinessBeerCommand();
        $command->id = $id;

        $this->get('command_bus')->handle($command);

        return $this->redirectToRoute('business.beer.list');
    }

    /**
     * Create the command from a business beer entity
     *
     * @param BusinessBeer $entity
     * @return EditBusinessBeerCommand
     */
    private function createEditBusinessBeerCommand(BusinessBeer $entity)
    {
        $command = new EditBusinessBeerCommand();

        $command->id = $entity->getId();
        $command->category = $entity->getCategory();
        $command->name = $entity->getName();
        $command->notes = $entity->getNotes();

        return $command;
    }
}
