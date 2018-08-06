<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\Payment;
use AppBundle\Form\PaymentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PaymentController
 *
 * @package AppBundle\Controller
 * @Route("/payments")
 */
class PaymentController extends Controller
{

    /**
     * @Route("", name="payment_all")
     * @return Response
     */
    public function allAction()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();

        $payments = $manager->getRepository(Payment::class)->findAll();

        return $this->render('AppBundle:Payment:all.html.twig', [
           'payments' => $payments
        ]);
    }

    /**
     * @Route("/new", name="payment_new")
     *
     * @param $request
     *
     * @return Response
     */
    public function newAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();
        $payment = new Payment();
        $form  = $this->createForm(PaymentType::class,$payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $invoices = $payment->getInvoices();
            foreach ($invoices as $invoice) {
                if ($payment->getStatus() == Payment::STATUS_SUCCESS) {
                    $invoice->setStatus(Invoice::STATUS_PAID);
                    $manager->persist($invoice);
                }
            }
            $manager->persist($payment);
            $manager->flush();
            $this->addFlash('success', 'Payment created successfully');
            return $this->redirectToRoute('payment_all');
        }

        return $this->render('AppBundle:Payment:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/{id}/edit", name="payment_edit")
     *
     * @param $request
     * @param $id
     * @return Response
     */
    public function editAction(Request $request, $id)
    {

        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();

        $payment = $manager->getRepository('AppBundle:Payment')->find($id);
        $form  = $this->createForm(PaymentType::class,$payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($payment);
            $manager->flush();
        }

        return $this->render('AppBundle:Payment:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/delete")
     */
    public function deleteAction()
    {
        return $this->render('AppBundle:Payment:delete.html.twig', array(
            // ...
        ));
    }

}
