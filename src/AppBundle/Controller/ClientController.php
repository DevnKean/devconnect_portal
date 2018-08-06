<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\ClientNote;
use AppBundle\Form\ClientNoteType;
use AppBundle\Form\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ClientController
 *
 * @package AppBundle\Controller
 * @Route("/clients")
 */
class ClientController extends Controller
{

    /**
     * @Route("", name="client_all")
     */
    public function allAction()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();
        $clients = $manager->getRepository('AppBundle:Client')->findAll();
        return $this->render('AppBundle:Client:all.html.twig', array(
            'clients' => $clients
        ));
    }

    /**
     * @Route("/new", name="client_new")
     *
     * @param $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($client);
            $manager->flush();
            $this->addFlash('success', 'Client Created');
            return $this->redirectToRoute('client_all');
        }
        return $this->render('AppBundle:Client:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/{id}/edit", name="client_edit")
     *
     * @param $request
     * @param $id
     *
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();
        $client = $manager->getRepository('AppBundle:Client')->find($id);
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($client);
            $manager->flush();
            $this->addFlash('success', 'Client Update');
            return $this->redirectToRoute('client_all');
        }

        return $this->render('AppBundle:Client:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/{id}/view", name="client_view")
     *
     * @param $request
     * @param $id
     *
     * @return Response
     */
    public function viewAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();
        $client = $manager->getRepository('AppBundle:Client')->find($id);
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($client);
            $manager->flush();
            $this->addFlash('success', 'Client Update');
            return $this->redirectToRoute('client_all');
        }

        $clientNote = new ClientNote();
        $noteForm = $this->createForm(ClientNoteType::class, $clientNote);
        $noteForm->handleRequest($request);

        if ($noteForm->isSubmitted() && $noteForm->isValid()) {
            $client->addClientNote($clientNote);
            $manager->persist($client);
            $manager->flush();
            $this->addFlash('success', 'Client Note Update');
            return $this->redirectToRoute('client_view', ['id' => $id]);
        }

        return $this->render('AppBundle:Client:view.html.twig', array(
            'form' => $form->createView(),
            'client' => $client,
            'noteForm' => $noteForm->createView()
        ));
    }

    /**
     * @Route("/{id}/delete", name="client_delete")
     */
    public function deleteAction()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        return $this->render('AppBundle:Client:delete.html.twig', array(
            // ...
        ));
    }

}
