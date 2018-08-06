<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ActivityLog;
use AppBundle\Entity\Contact;
use AppBundle\Entity\Contract;
use AppBundle\Entity\LogEntry;
use AppBundle\Entity\Profile;
use AppBundle\Entity\Supplier;
use AppBundle\Entity\SupplierNote;
use AppBundle\Entity\SupplierProfile;
use AppBundle\Form\SupplierNoteType;
use AppBundle\Form\SupplierType;
use http\Exception\InvalidArgumentException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class SupplierController
 *
 * @package AppBundle\Controller
 * @Route("/suppliers")
 */
class SupplierController extends Controller
{
    /**
     * @Route("/create", name="supplier_create")
     * @param Request $request
     * @return  Response
     */
    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $supplier = new Supplier();
        $note = new SupplierNote();
        $supplier->addSupplierNote($note);
        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($supplier);
            $manager->flush();
            $this->addFlash('success', 'Supplier Created');
            return $this->redirect($this->generateUrl('supplier_all'));
        }

        return $this->render('AppBundle:Supplier:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("", name="supplier_all")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allAction()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $manager =$this->getDoctrine()->getManager();
        $suppliers = $manager->getRepository('AppBundle:Supplier')->findAll();

        $approvedCount = 0;
        $pendingCount = 0;
        $feedbackCount = 0;
        $notStartedCount = 0;

        foreach ($suppliers as $supplier) {
            if ($supplier->isApproved()) {
                list ($incomplete, $pending, $feedback, $approved) = array_values($supplier->getProfileStatusCount());
                $approvedCount += $approved;
                $pendingCount += $pending;
                $feedbackCount += $feedback;
                $notStartedCount += $incomplete;
            }

        }

        $approvedSupplier = 0;
        $pendingSupplier = 0;
        $notStartedSupplier = 0;
        foreach ($suppliers as $supplier) {
            if ($supplier->isApproved()) {
                if ($supplier->getProfileOverallStatus() == SupplierProfile::STATUS_APPROVED) {
                    $approvedSupplier++;
                }
                if ($supplier->getProfileOverallStatus() == SupplierProfile::STATUS_PENDING) {
                    $pendingSupplier++;
                }
                if ($supplier->getProfileOverallStatus() == SupplierProfile::STATUS_INCOMPLETE) {
                    $notStartedSupplier++;
                }
            }
        }
        foreach ($suppliers as $supplier) {
            if ($supplier->isApproved()) {
                if ($supplier->getProfileOverallStatus() == SupplierProfile::STATUS_APPROVED) {
                    $approvedSupplier++;
                }
                if ($supplier->getProfileOverallStatus() == SupplierProfile::STATUS_PENDING) {
                    $pendingSupplier++;
                }
                if ($supplier->getProfileOverallStatus() == SupplierProfile::STATUS_INCOMPLETE) {
                    $notStartedSupplier++;
                }
            }
        }

        try {
            $contractSentCount = $manager->getRepository('AppBundle:Supplier')->getContractSentSuppliersCount(Contract::STATUS_SENT);
        } catch (\Exception $e) {
            $contractSentCount = 0;
        }

        try {
            $contractNeedToPrepareCount = $manager->getRepository('AppBundle:Supplier')->getContractSentSuppliersCount(Contract::STATUS_NEED_TO_PREPARE);
        } catch (\Exception $e) {
            $contractNeedToPrepareCount = 0;
        }

        return $this->render('AppBundle:Supplier:all.html.twig',
            compact(
                'suppliers',
                'approvedCount',
                'pendingCount',
                'notStartedCount',
                'approvedSupplier',
                'pendingSupplier',
                'notStartedSupplier',
                'feedbackCount',
                'contractSentCount',
                'contractNeedToPrepareCount'
            ));

    }

    /**
     * @Route("/{id}/edit", name="supplier_edit")
     * @Method({"GET", "POST"})
     * @param int $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $em = $this->getDoctrine()->getManager();
        $supplier = $em->getRepository('AppBundle:Supplier')->find($id);

        $form = $this->createForm(SupplierType::class, $supplier);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($supplier);
            $em->flush();
            $this->addFlash('success', 'Supplier Updated');
        }
        return $this->render('AppBundle:Supplier:edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/view", name="supplier_view")
     *
     * @param $id
     * @param $request
     *
     * @return Response
     */
    public function viewAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $manager = $this->getDoctrine()->getManager();
        $supplier = $manager->getRepository('AppBundle:Supplier')->find($id);
        $supplierProfiles = $manager->getRepository('AppBundle:SupplierProfile')->findBy(['supplier' => $supplier]);
        if (!$supplier) {
            throw new NotFoundHttpException();
        }
        $note = new SupplierNote();
        $note->setSupplier($supplier);
        $form = $this->createForm(SupplierNoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($note);
            $manager->flush();
            return $this->redirectToRoute('supplier_view', ['id' => $id]);
        }

        $notes = $supplier->getSupplierNotes();
        $activityLog = new ActivityLog($supplier);
        return $this->render('AppBundle:Supplier:view.html.twig', [
            'supplier' => $supplier,
            'activityLog' => $activityLog,
            'supplierProfiles' => $supplierProfiles,
            'form' => $form->createView(),
            'notes' => $notes
        ]);

    }

    /**
     * @Route("/{id}/profile/{profileId}/status/{status}/update", name="supplier_profile_update")
     *
     * @param $id
     * @param $profileId
     * @param $status
     *
     * @return Response
     */
    public function profileStatusUpdateAction($id, $profileId, $status, \Swift_Mailer $mailer)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        if (!in_array($status, SupplierProfile::getProfileStatus())) {
            throw new InvalidArgumentException();
        }

        $manager = $this->getDoctrine()->getManager();
        $supplier = $manager->getRepository('AppBundle:Supplier')->find($id);

        $profile = $manager->getRepository('AppBundle:Profile')->find($profileId);
        $slug = $profile->getSlug();
        if (!$supplier || !$profile) {
            throw new NotFoundHttpException();
        }

        $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy(['supplier' => $supplier, 'profile' => $profile]);
        $entries = $manager
            ->getRepository('AppBundle:LogEntry')
            ->findBy(['name' => $profile->getName(), 'user' => $supplier->getUserIds()]);

        foreach ($entries as $entry) {
            if ($entry->getFeedbackStatus() == LogEntry::STATUS_READY_FOR_FEEDBACK) {
                $entry->setFeedbackStatus(LogEntry::STATUS_FEEDBACK);
            }

            if ($entry->getFeedbackStatus() == LogEntry::STATUS_READY_FOR_APPROVAL) {
                $entry->setFeedbackStatus(LogEntry::STATUS_APPROVED);
            }
        }

        $supplierProfile->setStatus($status);
        $manager->persist($supplierProfile);
        $manager->flush();

        try {
            if ($status == SupplierProfile::STATUS_APPROVED && $supplier->getProfileContact()) {
                $message = (new \Swift_Message("Your $profile profile section has been approved"))
                    ->setFrom('admin@cxconnect.com.au', 'CX Connect')
                    ->setTo($supplier->getProfileContact()->getEmail())
                    ->setBody(
                        $this->renderView(
                            'emails/supplier/profile.approved.html.twig',
                            array('contact' => $supplier->getProfileContact(), 'profile' => $profile)
                        ),
                        'text/html'
                    );
                $mailer->send($message);
            } elseif ($status == SupplierProfile::STATUS_FEEDBACK && $supplier->getProfileContact()) {
                $message = (new \Swift_Message("Action required: Feedback for your $profile profile section"))
                    ->setFrom('admin@cxconnect.com.au', 'CX Connect')
                    ->setTo($supplier->getProfileContact()->getEmail())
                    ->setBody(
                        $this->renderView(
                            'emails/supplier/profile.feedback.html.twig',
                            array('contact' => $supplier->getProfileContact(), 'profile' => $profile)
                        ),
                        'text/html'
                    );
                $mailer->send($message);
            }
        } catch (\Exception $e) {

        }


        $this->addFlash('success', 'Profile Status updated');

        return $this->redirectToRoute('supplier_activity_log', compact('id', 'slug'));
    }

    /**
     * @Route("/{id}/delete", name="supplier_delete")
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();

        $supplier = $manager->getRepository('AppBundle:Supplier')->find($id);

        if (!$supplier) {
            throw new NotFoundHttpException();
        }

        $manager->remove($supplier);
        $manager->flush();
        $this->addFlash('success', 'Supplier deleted successfully');
        return $this->redirectToRoute('supplier_all');
    }

    /**
     * @Route("/{id}/profile/{slug}/change-logs",name="supplier_activity_log")
     *
     * @param $request
     * @param $id
     * @param $slug
     *
     * @return Response
     */
    public function changeLogAction(Request $request, $id, $slug)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $em = $this->getDoctrine()->getManager();
        $supplier = $em->getRepository('AppBundle:Supplier')->find($id);
        $profileName = Profile::getProfile($slug);
        $profile = $supplier->getProfile($profileName);
        $supplierProfile = $em->getRepository('AppBundle:SupplierProfile')->findOneBy(['supplier' => $supplier, 'profile'=> $profile]);

        $entries = $em
            ->getRepository('AppBundle:LogEntry')
            ->findBy(['name' => Profile::getProfile($slug), 'user' => $supplier->getUserIds()], ['loggedAt' => 'desc']);

        $readyToApprovalCount = count(array_filter($entries, function (LogEntry $entry) {
            return $entry->getFeedbackStatus() == LogEntry::STATUS_READY_FOR_APPROVAL;
        }));

        $readyForFeedbackCount = count(array_filter($entries, function (LogEntry $entry) {
            return $entry->getFeedbackStatus() == LogEntry::STATUS_READY_FOR_FEEDBACK;
        }));

        $pendingReviewCount = count(array_filter($entries, function (LogEntry $entry) {
            return $entry->getFeedbackStatus() == LogEntry::STATUS_PENDING;
        }));


        $entries = $this
            ->get('activity_log.formatter')
            ->format($entries);


        return $this->render(
            'AppBundle:Supplier:change-log.html.twig',
            compact('entries', 'supplier', 'profile', 'supplierProfile', 'readyToApprovalCount', 'readyForFeedbackCount', 'pendingReviewCount')
         );

    }

    /**
     * @Route("/{id}/entry/{entryId}/approve", name="supplier_entry_approve")
     *
     * @param $id
     * @param $entryId
     *
     * @return Response
     */
    public function approveAction($id, $entryId)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();

        $entry = $manager->getRepository('AppBundle:LogEntry')->find($entryId);

        $profile = $manager->getRepository('AppBundle:Profile')->findOneBy(['name' => $entry->getName()]);

        if ($entry->getFeedbackStatus() == LogEntry::STATUS_READY_FOR_APPROVAL) {
            $entry->setFeedbackStatus(LogEntry::STATUS_PENDING);
        } else {
            $entry->setFeedbackStatus(LogEntry::STATUS_READY_FOR_APPROVAL);
        }

        $manager->persist($entry);
        $manager->flush();
        $this->addFlash('success', 'Entry Status updated');
        return $this->redirectToRoute('supplier_activity_log', ['id' => $id, 'slug' => $profile->getSlug()]);
    }

    /**
     * @Route("/{id}/profile/{profileId}/approveAll", name="supplier_profile_approveAll")
     *
     * @param $id
     * @param $profileId
     *
     * @return Response
     */
    public function approveAllAction($id, $profileId)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();

        $profile = $manager->getRepository('AppBundle:Profile')->find($profileId);

        $supplier = $manager->getRepository('AppBundle:Supplier')->find($id);
        $entries = $manager
            ->getRepository('AppBundle:LogEntry')
            ->findBy(['name' => $profile->getName(), 'user' => $supplier->getUserIds(), 'feedbackStatus' => [LogEntry::STATUS_PENDING, LogEntry::STATUS_FEEDBACK, LogEntry::STATUS_FEEDBACK_RESPONSE]]);

        foreach ($entries as $entry) {
            $entry->setFeedbackStatus(LogEntry::STATUS_READY_FOR_APPROVAL);
            $manager->persist($entry);
        }

        $manager->flush();
        $this->addFlash('success', 'Entry Status updated');
        return $this->redirectToRoute('supplier_activity_log', ['id' => $id, 'slug' => $profile->getSlug()]);
    }

    /**
     * @Route("/{id}/entry/{entryId}/feedback", name="supplier_entry_feedback")
     *
     * @param $id
     * @param $request
     * @param $entryId
     *
     * @return string
     */
    public function feedbackAction(Request $request, $id, $entryId)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        
        $manager = $this->getDoctrine()->getManager();

        $entry = $manager->getRepository('AppBundle:LogEntry')->find($entryId);
        $form = $this
            ->createFormBuilder()
            ->setAction($this->generateUrl('supplier_entry_feedback', ['id' => $id, 'entryId' => $entryId]))
            ->add(
                'feedback',
                TextareaType::class,
                [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Please add you feedback',
                        'rows' => 10,
                        'class' => 'resize-vertically',
                    ],
                ]
            )
            ->getForm();

        $form->handleRequest($request);
        $profile = $manager->getRepository('AppBundle:Profile')->findOneBy(['name' => $entry->getName()]);
        if ($form->isSubmitted() && $form->isValid()) {
            $originalFeedback = $entry->getFeedback();
            $feedback = $form->getData()['feedback'];
            $dateString = (new \DateTime('now', new \DateTimeZone('Australia/Melbourne')))->format('Y-m-d H:i:s');
            $newFeedback = $originalFeedback . "($dateString) $feedback<br />" ;
            $entry->setFeedback($newFeedback);
            $entry->setFeedbackStatus(LogEntry::STATUS_READY_FOR_FEEDBACK);
            $entry->setIsRead(false);
            $manager->persist($entry);
            $manager->flush();
            $this->addFlash('success', 'Feedback updated');
            return $this->redirectToRoute('supplier_activity_log', ['id' => $id, 'slug' => $profile->getSlug()]);
        }

        return $this->render('form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/profile/{profileId}/approve", name="supplier_profile_approve")
     *
     * @param $id
     * @param $profileId
     *
     * @return Response
     */
    public function profileViewAction($id, $profileId)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $manager = $this->getDoctrine()->getManager();

        $supplier = $manager->getRepository('AppBundle:Supplier')->find($id);
        $profile = $manager->getRepository('AppBundle:Profile')->find($profileId);

        $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy([
            'supplier' => $supplier,
            'profile' => $profile
        ]);

        $supplierProfile->setStatus(SupplierProfile::STATUS_APPROVED);
        $manager->flush();

        return $this->redirectToRoute('supplier_view', ['id' => $id]);
    }

    /**
     * @Route("/{id}/profiles", name="supplier_profiles")
     */
    public function profilesAction()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $manager = $this->getDoctrine()->getManager();

        $supplierProfiles = $manager->getRepository('AppBundle:SupplierProfile')->findAll();

        return $this->render('@App/Supplier/profiles.html.twig', [
           'supplierProfiles' => $supplierProfiles
        ]);
    }
}
