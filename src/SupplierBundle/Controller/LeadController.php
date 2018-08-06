<?php

namespace SupplierBundle\Controller;

use AppBundle\Entity\AccountNote;
use AppBundle\Entity\Form;
use AppBundle\Entity\LeadNote;
use AppBundle\Entity\LeadStatusLog;
use AppBundle\Entity\LeadTracker;
use AppBundle\Entity\User;
use AppBundle\Form\LeadNoteType;
use AppBundle\Service\LeadViewer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class LeadController extends Controller
{
    /**
     * @Route("/leads/new", name="supplier_leads_new")
     */
    public function newAction()
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $supplier = $user->getSupplier();
        $manager = $this->getDoctrine()->getManager();
        $leadSuppliers = $manager->getRepository('AppBundle:LeadSupplier')->findNewLeads($supplier);
        return $this->render('SupplierBundle:Lead:all.html.twig', [
            'leadSuppliers' => $leadSuppliers
        ]);
    }

    /**
     * @Route("/leads/manage", name="supplier_leads_manage")
     *
     * @return Response
     */
    public function manageAction()
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $manager = $this->getDoctrine()->getManager();

        $leadSuppliers = $manager->getRepository('AppBundle:LeadSupplier')->findActiveLeads($supplier);
        return $this->render('SupplierBundle:Lead:all.html.twig', [
            'leadSuppliers' => $leadSuppliers
        ]);
    }

    /**
     * @Route("/lead/{id}/edit", name="supplier_lead_edit")
     *
     * @param Request $request
     * @param integer $id
     * @param $mailer
     *
     * @return Response
     */
    public function editAction(Request $request, $id, \Swift_Mailer $mailer)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $manager = $this->getDoctrine()->getManager();
        $supplier = $user->getSupplier();
        $lead = $manager->getRepository('AppBundle:Lead')->find($id);
        $leadSupplier = $manager->getRepository('AppBundle:LeadSupplier')->findOneBy(['lead' => $lead, 'supplier' => $supplier]);
        $originalStatus = $leadSupplier->getLeadStatus();
        if (!$lead) {
            throw new NotFoundHttpException();
        }

        $leadNote = new LeadNote();
        $leadNote->setLead($lead);
        $leadNote->setSupplier($supplier);
        $form = $this->createForm(LeadNoteType::class, $leadNote);

        $leadForm = $this->createFormBuilder($leadSupplier)
                     ->add('leadStatus', ChoiceType::class, [
                         'label' => false,
                         'choices' => array_combine(LeadNote::getNoteStatuses(), LeadNote::getNoteStatuses())
                     ])
                     ->getForm();

        $leadForm->handleRequest($request);
        $leadStatusLogs = $manager->getRepository('AppBundle:LeadStatusLog')->findBy(['supplier' => $user->getSupplier(), 'lead' => $leadSupplier->getLead()]);
        if ($leadForm->isSubmitted() && $leadForm->isValid()) {
            $manager->persist($leadSupplier);
            if ($originalStatus != $leadSupplier->getLeadStatus()) {
                $accountNote = new AccountNote();
                $accountNote->setNote(sprintf("Lead status changed to '%s'", $leadSupplier->getLeadStatus()));
                $accountNote->setSupplier($supplier);
                $manager->persist($accountNote);
                $lead = $leadSupplier->getLead();
                $statusLog = new LeadStatusLog();
                $statusLog->setLead($lead);
                $statusLog->setStatus($leadSupplier->getLeadStatus());
                $statusLog->setSupplier($user->getSupplier());
                $manager->persist($statusLog);

                $message = (new \Swift_Message('Status Change'))
                    ->setFrom('admin@cxconnect.com.au', 'CX Connect')
                    ->setTo('admin@cxconnect.com.au')
                    ->setBody(
                        $this->renderView(
                            ':emails/admin/lead:status.change.html.twig',
                            [
                                'leadSupplier' => $leadSupplier,
                                'originalStatus' => $originalStatus
                            ]
                        ),
                        'text/html'
                    );
                $mailer->send($message);
            }

            $manager->flush();
            $this->addFlash('success', 'Lead status successfully updated');
            return $this->redirectToRoute('supplier_lead_edit', ['id' => $id]);
        }

        $viewer = new LeadViewer($manager, $lead);
        $result = $viewer->process();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($leadNote);
            $manager->flush();
            $this->addFlash('success', 'Lead Notes create successfully');
            return $this->redirectToRoute('supplier_lead_edit', ['id' => $id]);
        }

        $leadNotes = $manager->getRepository('AppBundle:LeadNote')->findBy(['lead' => $lead, 'supplier' => $supplier], ['createdAt' => 'asc']);
        return $this->render('SupplierBundle:Lead:lead.html.twig', [
            'form' => $form->createView(),
            'leadData' => $result,
            'lead' => $lead,
            'leadNotes' => $leadNotes,
            'leadForm' => $leadForm->createView(),
            'excludeIds' => Form::$excludeQuestionIds,
            'leadStatusLogs' => $leadStatusLogs
        ]);
    }

    /**
     * @Route("/leads/expired", name="supplier_leads_expired")
     */
    public function expiredAction()
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $manager = $this->getDoctrine()->getManager();
        $supplier = $user->getSupplier();

        $leadSuppliers = $manager->getRepository('AppBundle:LeadSupplier')->findExpiredLeads($supplier);
        return $this->render('SupplierBundle:Lead:expired.html.twig', [
            'leadSuppliers' => $leadSuppliers
        ]);
    }

    /**
     * @Route("/lead/{id}/archive", name="supplier_lead_archive")
     *
     * @param $request
     * @param $id
     *
     * @return Response
     */
    public function archiveLeadAction(Request $request, $id)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $manager = $this->getDoctrine()->getManager();
        $lead = $manager->getRepository('AppBundle:Lead')->find($id);
        $leadSupplier = $manager->getRepository('AppBundle:LeadSupplier')->findOneBy(['lead' => $lead, 'supplier' => $supplier]);
        $leadSupplier->setLeadStatus(LeadNote::getArchivedStatus());
        $manager->persist($leadSupplier);
        $manager->flush();
        return $this->redirectToRoute('supplier_leads_expired');
    }

    /**
     *
     * @Route("/archived", name="supplier_leads_archived")
     * @return Response
     */
    public function archivedAction()
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $manager = $this->getDoctrine()->getManager();
        $supplier = $user->getSupplier();

        $leadSuppliers = $manager->getRepository('AppBundle:LeadSupplier')->findArchivedLeads($supplier);
        return $this->render('SupplierBundle:Lead:archived.html.twig', [
            'leadSuppliers' => $leadSuppliers
        ]);
    }
}
