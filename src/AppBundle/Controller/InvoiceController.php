<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invoice;
use AppBundle\Form\InvoiceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InvoiceController
 *
 * @package AppBundle\Controller
 * @Route("/invoices")
 */
class InvoiceController extends Controller
{
    /**
     * @Route("/create", name="invoice_create")
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $invoice = new Invoice();
        $form = $this->createForm(InvoiceType::class, $invoice);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($invoice);
            $manager->flush();
            $this->addFlash('success', 'Invoice Created');
            return $this->redirectToRoute('invoice_all');
        }

        return $this->render('AppBundle:Invoice:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("", name="invoice_all")
     */
    public function allAction()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $manager = $this->getDoctrine()->getManager();

        $invoices = $manager->getRepository('AppBundle:Invoice')->findBy([], ['createdAt' => 'desc']);

        return $this->render('AppBundle:Invoice:all.html.twig', compact('invoices'));
    }

    /**
     * @Route("/{id}/view", name="invoice_view")
     *
     * @param $id
     * @param id
     *
     * @return Response
     */
    public function viewAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $manager = $this->getDoctrine()->getManager();

        $invoice = $manager->find('AppBundle:Invoice', $id);

        return $this->render('AppBundle:Invoice:edit.html.twig', array(
            'invoice' => $invoice
        ));
    }

    /**
     * @Route("/{id}/edit", name="invoice_edit")
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

        $invoice = $manager->find('AppBundle:Invoice', $id);

        $form = $this->createForm(InvoiceType::class, $invoice);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($invoice);
            $manager->flush();
            $this->addFlash('success', 'Invoice Updated');
            return $this->redirectToRoute('invoice_all');
        }

        return $this->render('AppBundle:Invoice:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/data", name="invoice_data")
     * @Method({"POST"})
     * @param $request
     *
     * @return string
     */
    public function invoiceDataAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();

        if ($request->getMethod() == 'POST')
        {
            $ids = $request->get('ids');
            $invoices = $manager->getRepository('AppBundle:Invoice')->findBy(['id' => $ids]);
        }

        return new Response($this->renderView('@App/Invoice/data.html.twig',
            compact('invoices')));
    }

}
