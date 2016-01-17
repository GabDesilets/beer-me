<?php

namespace AppBundle\Controller\Business;

use AppBundle\Command\Business\EditBusinessCommand;
use AppBundle\Form\BusinessType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/business")
 */
class HomeController extends Controller
{
    /**
     * @Route("/", name="business.homepage")
     */
    public function homeAction()
    {
        return $this->render(':business:home.html.twig');
    }

    /**
     * @Route("/profile", name="fos_user_profile_show")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function profileFunction(Request $request)
    {
        $business = $this->get('session.business')->getBusiness();

        $command = new EditBusinessCommand();
        $command->id = $business->getId();
        $command->name = $business->getName();
        $command->address = $business->getAddress();
        $command->phone = $business->getPhone();
        $command->administratorEmail = $business->getAdministratorUser()->getEmail();

        $form = $this->createForm(BusinessType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('command_bus')->handle($command);
            return $this->redirectToRoute('business.homepage');
        }

        return $this->render(':business:profile.html.twig', ['form' => $form->createView()]);
    }
}
