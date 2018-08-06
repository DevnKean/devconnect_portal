<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Form;
use AppBundle\Entity\Lead;
use AppBundle\Entity\LeadNote;
use AppBundle\Entity\LeadSupplier;
use AppBundle\Entity\LeadTracker;
use AppBundle\Entity\User;
use AppBundle\Form\LeadApproveType;
use AppBundle\Form\LeadNoteType;
use AppBundle\Form\LeadType;
use AppBundle\Service\GravityClient;
use AppBundle\Service\LeadSync;
use AppBundle\Service\LeadViewer;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class Lead
 *
 * @package AppBundle\Controller
 * @Route("/leads")
 */
class LeadController extends Controller
{

    /**
     * @Route("/{id}/manage", name="lead_manage")
     *
     * @param Request $request
     * @param $mailer
     * @param int $id
     * @return Response
     */
    public function manageAction(Request $request, $id, \Swift_Mailer $mailer)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $manager = $this->getDoctrine()->getManager();
        $lead = $manager->find('AppBundle:Lead', $id);

        $originalLeadSuppliers = new ArrayCollection();
        $originalStatuses = [];
        foreach ($lead->getLeadSuppliers() as $leadSupplier) {
            $originalLeadSuppliers->add($leadSupplier);
            $originalStatuses[$leadSupplier->getId()] = $leadSupplier->getResult();
        }

        $form = $this->createForm(LeadType::class, $lead);
        $leadNote = new LeadNote();
        $leadNote->setLead($lead);
        $leadNote->setSupplier($user->getSupplier());
        $leadNotesForm = $this->createForm(LeadNoteType::class, $leadNote);
        $viewer = new LeadViewer($manager, $lead);
        $result = $viewer->process();
        $form->handleRequest($request);
        $leadNotesForm->handleRequest($request);
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $leadNotes = $manager->getRepository('AppBundle:LeadNote')->findBy(['lead' => $lead, 'supplier' => $user->getSupplier()]);
        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($originalLeadSuppliers as $leadSupplier) {
                if (!$lead->getLeadSuppliers()->contains($leadSupplier)) {
                    $manager->remove($leadSupplier);
                }
            }

            foreach ($lead->getLeadSuppliers() as $leadSupplier) {
                if (!$originalLeadSuppliers->contains($leadSupplier)) {
                    try {
                        $message = (new \Swift_Message('New lead from CX Connect'))
                            ->setFrom('admin@cxconnect.com.au', 'CX Connect')
                            ->setTo($leadSupplier->getSupplier()->getLeadContact()->getEmail())
                            ->setBody(
                                $this->renderView(
                                    ':emails/supplier/lead:new.lead.html.twig',
                                    ['leadSupplier' => $leadSupplier]
                                ),
                                'text/html'
                            );
                        $mailer->send($message);
                    } catch (\Exception $e) {
                        $this->addFlash('danger', 'Failed to send lead email to supplier');
                    }

                } else {
                    if ($originalStatuses[$leadSupplier->getId()] !== $leadSupplier->getResult()) {
                        try {
                            $message = (new \Swift_Message('Lead Status Change - CX Connect'))
                                ->setFrom('admin@cxconnect.com.au', 'CX Connect')
                                ->setTo($leadSupplier->getSupplier()->getLeadContact()->getEmail())
                                ->setBody(
                                    $this->renderView(
                                        ':emails/supplier/lead:status.change.html.twig',
                                        ['leadSupplier' => $leadSupplier, 'originalStatus' => $originalStatuses[$leadSupplier->getId()]]
                                    ),
                                    'text/html'
                                );
                            $mailer->send($message);
                        } catch (\Exception $e) {
                            $this->addFlash('danger', 'Failed to send lead email to supplier');
                        }

                    }
                }
            }

            $leadLostCount = $lead->getLeadSuppliers()->filter(function (LeadSupplier $leadSupplier) {
                return $leadSupplier->getResult() == LeadSupplier::RESULT_LOST;
            })->count();

            if ($leadLostCount == $lead->getLeadSuppliers()->count()) {
                $lead->setStatus(Lead::STATUS_DECLINED);
            }

            $manager->persist($lead);
            $manager->flush();
            return $this->redirectToRoute('leads_active');
        }

        if ($leadNotesForm->isSubmitted() && $leadNotesForm->isValid()) {
            $manager->persist($leadNote);
            $manager->flush();
            return $this->redirectToRoute('lead_manage', ['id' => $id]);
        }

        return $this->render(
            'AppBundle:Lead:manage.html.twig', [
            'form' => $form->createView(),
            'leadData' => $result,
            'lead' => $lead,
            'leadNotes' => $leadNotes,
            'noteForm' => $leadNotesForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}/approve", name="lead_approve")
     *
     * @param Request $request
     * @param int $id
     *
     * @return Response
     */
    public function approveAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $manager = $this->getDoctrine()->getManager();
        $lead = $manager->find('AppBundle:Lead', $id);
        $originalStatus = $lead->getStatus();

        $form = $this->createForm(LeadApproveType::class, $lead);
        $form->handleRequest($request);

        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $leadNote = new LeadNote();
        $leadNote->setSupplier($user->getSupplier());
        $leadNote->setLead($lead);
        $noteForm = $this->createForm(LeadNoteType::class, $leadNote);
        $noteForm->handleRequest($request);
        $leadNotes = $manager->getRepository('AppBundle:LeadNote')->findBy(['lead' => $lead, 'supplier' => $user->getSupplier()]);
        if ($noteForm->isSubmitted() && $noteForm->isValid()) {
            $manager->persist($leadNote);
            $manager->flush();
            return $this->redirectToRoute('lead_approve', ['id' => $id]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            if ($originalStatus !== $lead->getStatus()) {
                $tracker = new LeadTracker();
                $tracker->setLead($lead);
                $tracker->setStatus($lead->getStatus());
                $manager->persist($tracker);
            }

            $manager->persist($lead);
            $manager->flush();
            return $this->redirectToRoute('leads_pending');
        }
        $viewer = new LeadViewer($manager, $lead);
        $result = $viewer->process();

        return $this->render('AppBundle:Lead:approve.html.twig', [
            'leadData' => $result,
            'form' => $form->createView(),
            'noteForm' => $noteForm->createView(),
            'leadNotes' => $leadNotes,
            'lead' => $lead
        ]);
    }

    /**
     * @Route("/pending", name="leads_pending")
     */
    public function pendingLeadsAction()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $manager = $this->getDoctrine()->getManager();
        $leads = $manager->getRepository('AppBundle:Lead')->findBy(['status' => [Lead::STATUS_PENDING, Lead::STATUS_CONTACT_REQUIRED]]);
        return $this->render('AppBundle:Lead:pending.html.twig', compact('leads'));
    }

    /**
     * @Route("/active", name="leads_active")
     */
    public function activeLeadsAction()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $manager = $this->getDoctrine()->getManager();
        $leads = $manager->getRepository('AppBundle:Lead')->findBy(['status' => Lead::STATUS_APPROVE]);
        return $this->render('AppBundle:Lead:active.html.twig', compact('leads'));
    }

    /**
     * @Route("/expired", name="leads_expired")
     */
    public function expiredLeadsAction()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $manager = $this->getDoctrine()->getManager();
        $leads = $manager->getRepository('AppBundle:Lead')->findBy(['status' => [Lead::STATUS_DECLINED, Lead::STATUS_EXPIRED, Lead::STATUS_UNABLE_TO_CONTACT]]);
        return $this->render('AppBundle:Lead:expired.html.twig', compact('leads'));
    }

    /**
     * @Route("/{id}/sync", name="lead_sync")
     *
     * @param $id
     *
     * @return Response
     */
    public function syncAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();
        /** @var Lead $lead */
        $lead = $manager->getRepository('AppBundle:Lead')->findOneBy(['entryId' => $id]);
        if (!$lead) {
            throw new NotFoundHttpException();
        }

        if ($lead->getForm()->getSource() == Form::SOURCE_CX_CENTRAL) {
            $client = new GravityClient(
                $this->getParameter('cxcentral.host'),
                $this->getParameter('cxcentral.public_key'),
                $this->getParameter('cxcentral.private_key'));
        } else {
            $client = new GravityClient(
                $this->getParameter('cxconnect.host'),
                $this->getParameter('cxconnect.public_key'),
                $this->getParameter('cxconnect.private_key'));
        }
        $leadSync = new LeadSync($manager, $lead, $client);
        $leadSync->process();
        $this->addFlash('success', 'Lead sync successful');
        return $this->redirectToRoute('leads_pending');
    }

}
