<?php

namespace AppBundle\Controller;

use AppBundle\Entity\LeadSupplier;
use AppBundle\Entity\SupplierInvoice;
use AppBundle\Form\SupplierInvoiceType;
use AppBundle\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SupplierInvoiceController
 *
 * @package AppBundle\Controller
 * @Route("/supplier-invoices")
 */
class SupplierInvoiceController extends Controller
{
    /**
     * @Route("", name="supplier_invoices_all")
     */
    public function allAction()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $manager = $this->getDoctrine()->getManager();

        $supplierInvoices = $manager->getRepository('AppBundle:SupplierInvoice')->findAll();
        return $this->render('AppBundle:SupplierInvoice:all.html.twig', array(
            'supplierInvoices' => $supplierInvoices
        ));
    }

    /**
     * @Route("/new", name="supplier_invoices_new")
     * @param $request
     * @param $fileUploader
     * @return Response
     */
    public function newAction(Request $request, FileUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $supplierInvoice = new SupplierInvoice();
        $form = $this->createForm(SupplierInvoiceType::class, $supplierInvoice);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $supplierInvoice->getFile();
            $fileName = $fileUploader->upload($file);
            $supplierInvoice->setFile($fileName);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($supplierInvoice);
            $manager->flush();
            $this->addFlash('success', 'Supplier Invoice Created');
            return $this->redirectToRoute('supplier_invoices_all');
        }

        return $this->render('AppBundle:SupplierInvoice:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/{id}/edit", name="supplier_invoices_edit")
     * @param $request
     * @param $fileUploader
     * @param $id
     *
     * @return Response
     */
    public function editAction(Request $request, $id, FileUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();
        $supplierInvoice =  $manager->find('AppBundle:SupplierInvoice', $id);
        $filePath = $supplierInvoice->getFile();
        $form = $this->createForm(SupplierInvoiceType::class, $supplierInvoice);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($file = $supplierInvoice->getFile()) {
                $fileName = $fileUploader->upload($file);
                $supplierInvoice->setFile($fileName);
            } else {
                $supplierInvoice->setFile($filePath);
            }
            $manager->persist($supplierInvoice);
            $manager->flush();
            $this->addFlash('success', 'Supplier Invoice Updated');
            return $this->redirectToRoute('supplier_invoices_all');
        }

        return $this->render('AppBundle:SupplierInvoice:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/delete")
     */
    public function deleteAction()
    {
        return $this->render('AppBundle:SupplierInvoice:delete.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/{id}/invoice-data", name="supplier_invoice_data")
     * @param $id
     *
     * @return string;
     */
    public function invoiceDataAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();
        $supplierInvoice = $manager->getRepository('AppBundle:SupplierInvoice')->find($id);
        /** @var LeadSupplier $campaign */
        $campaign =  $supplierInvoice->getLeadSupplier();
        $supplier = $campaign->getSupplier();
        $contactPaymentTerm = $supplier->getContract()->getPaymentTerm();
        $commissionRate = $supplierInvoice->findCommissionRate();
        $commission = $commissionRate * $supplierInvoice->getTotal();
        $tier = $supplierInvoice->findCommissionTier();
        $amount = $supplierInvoice->getTotal();
        $paymentDueDate = $supplierInvoice->getPaymentDueDate();
        return new Response($this->renderView('@App/SupplierInvoice/invoice-data.html.twig',
            compact('commissionRate',
                'tier',
                'commission',
                'amount',
                'paymentDueDate',
                'supplier',
                'contactPaymentTerm',
                'supplierInvoice'
            )));
    }

}
