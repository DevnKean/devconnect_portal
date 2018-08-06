<?php

namespace SupplierBundle\Controller;

use AppBundle\Entity\ChannelSupport;
use AppBundle\Entity\Commercial;
use AppBundle\Entity\Contact;
use AppBundle\Entity\Customer;
use AppBundle\Entity\DataAcquisition;
use AppBundle\Entity\Experience;
use AppBundle\Entity\Location;
use AppBundle\Entity\LocationTimetable;
use AppBundle\Entity\LogEntry;
use AppBundle\Entity\MinimumVolume;
use AppBundle\Entity\Profile;
use AppBundle\Entity\Reference;
use AppBundle\Entity\Service;
use AppBundle\Entity\SupplierProfile;
use AppBundle\Entity\SupportFunction;
use AppBundle\Entity\Technology;
use AppBundle\Entity\Tender;
use AppBundle\Entity\User;
use AppBundle\Entity\WorkFromHome;
use AppBundle\Form\CommercialType;
use AppBundle\Form\DataAcquisitionType;
use AppBundle\Form\MinimumVolumeType;
use AppBundle\Form\SupplierProfileType;
use AppBundle\Form\SupplierUserType;
use AppBundle\Form\TenderType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SupplierBundle\Form\SupplierAwardType;
use SupplierBundle\Form\SupplierCertificationType;
use SupplierBundle\Form\SupplierChannelSupportType;
use SupplierBundle\Form\SupplierContactType;
use SupplierBundle\Form\SupplierCurrentReferenceType;
use SupplierBundle\Form\SupplierCustomerType;
use SupplierBundle\Form\SupplierDataAcquisitionProviderType;
use SupplierBundle\Form\SupplierExperienceType;
use SupplierBundle\Form\SupplierInformationType;
use SupplierBundle\Form\SupplierLocationType;
use SupplierBundle\Form\SupplierPastReferenceType;
use SupplierBundle\Form\SupplierSupportFunctionType;
use SupplierBundle\Form\SupplierTechnologyType;
use SupplierBundle\Form\SupplierWorkFromHomeType;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ProfileController
 *
 * @package AppBundle\Controller
 * @Route("/profile")
 */
class ProfileController extends Controller
{

    /**
     * @Route("/user", name="profile_user")
     *
     * @param $request
     * @param $passwordEncoder
     *
     * @return Response
     */
    public function userAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $form = $this->createForm(SupplierUserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            if (!empty($user->getPlainPassword())) {
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            }
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Profile Updated');

            return $this->redirectToRoute('profile_user');
        }

        return $this->render('SupplierBundle:Profile:user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/information", name="profile_information")
     *
     * @param $request
     *
     * @return Response
     */
    public function businessInformationAction(Request $request)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $form = $this->createForm(SupplierInformationType::class, $supplier);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($supplier);
            $manager->flush();
            $this->addFlash('success', 'Business Information Updated');

            return $this->redirectToRoute('profile_information');
        }

        return $this->render('SupplierBundle:Profile:information.html.twig', [
            'form' => $form->createView(),
            'supplier' => $supplier,
        ]);
    }

    /**
     * @Route("/contacts", name="profile_contacts")
     *
     * @param Request $request
     * @param         $mailer
     *
     * @return Response
     */
    public function contactsAction(Request $request, Swift_Mailer $mailer)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $manager = $this->getDoctrine()->getManager();
        foreach (Contact::getTypes($supplier) as $type) {
            $exist = false;
            foreach ($supplier->getContacts() as $contact) {
                if ($type == $contact->getType()) {
                    $exist = true;
                    continue;
                }
            }
            if (!$exist) {
                $contact = new Contact();
                $contact->setType($type);
                $supplier->addContact($contact);
            }
        }

        $logEntries = $manager->getRepository('AppBundle:LogEntry')->findBy([
            'name' => Profile::PROFILE_CONTACT,
            'user' => $supplier->getUserIds(),
            'feedbackStatus' => [LogEntry::STATUS_FEEDBACK, LogEntry::STATUS_FEEDBACK_RESPONSE],
            'isRead' => false,
        ], ['loggedAt' => 'desc']);
        $hasRepliedCount = 0;
        foreach ($logEntries as $logEntry) {
            if ($logEntry->getFeedbackStatus() == LogEntry::STATUS_FEEDBACK_RESPONSE) {
                $hasRepliedCount++;
            }
        }
        $hasReplied = $hasRepliedCount == count($logEntries);
        $entries = $this
            ->get('activity_log.formatter')
            ->format($logEntries);

        $form = $this->createForm(SupplierContactType::class, $supplier);

        $form->handleRequest($request);
        $profile = $supplier->getProfile(Profile::PROFILE_CONTACT);
        if ($form->isSubmitted() && $form->isValid()) {
            $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy([
                'supplier' => $supplier,
                'profile' => $profile,
            ]);
            $supplierProfile->setStatus(SupplierProfile::STATUS_PENDING);
            foreach ($logEntries as $logEntry) {
                $logEntry->setIsRead(true);
            }
            $manager->persist($supplier);
            $manager->flush();
            $this->sendProfileNotification($supplier, $profile);
            $this->addFlash('success', 'Contacts Updated');

            return $this->redirectToRoute($profile->getRoute());
        }

        return $this->render('SupplierBundle:Profile:contacts.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'types' => array_values(Contact::getTypes($supplier)),
            'status' => $supplier->getProfileStatus(Profile::PROFILE_CONTACT),
            'profile' => $profile,
            'entries' => $entries,
            'supplier' => $supplier,
            'hasReplied' => $hasReplied,
            'hasRepliedCount' => $hasRepliedCount,
        ]);
    }

    /**
     * @Route("/locations", name="profile_locations")
     * @param Request $request
     * @param         $mailer
     *
     * @return Response
     */
    public function locationsAction(Request $request, Swift_Mailer $mailer)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $manager = $this->getDoctrine()->getManager();
        $profile = $supplier->getProfile(Profile::PROFILE_LOCATION);

        if (!$supplier->getLocations()->count()) {
            $location = new Location();
            $supplier->addLocation($location);
        }
        $originalLocations = new ArrayCollection();
        foreach ($supplier->getLocations() as $location) {
            $originalLocations->add($location);
        }

        $status = $supplier->getProfileStatus(Profile::PROFILE_LOCATION);
        $logEntries = $manager->getRepository('AppBundle:LogEntry')->findBy([
            'name' => Profile::PROFILE_LOCATION,
            'user' => $supplier->getUserIds(),
            'feedbackStatus' => [LogEntry::STATUS_FEEDBACK, LogEntry::STATUS_FEEDBACK_RESPONSE],
            'isRead' => false,
        ], ['loggedAt' => 'desc']);
        $hasRepliedCount = 0;
        foreach ($logEntries as $logEntry) {
            if ($logEntry->getFeedbackStatus() == LogEntry::STATUS_FEEDBACK_RESPONSE) {
                $hasRepliedCount++;
            }
        }
        $hasReplied = $hasRepliedCount == count($logEntries);
        $entries = $this
            ->get('activity_log.formatter')
            ->format($logEntries);

        $form = $this->createForm(SupplierLocationType::class, $supplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                foreach ($originalLocations as $location) {
                    if (!$supplier->getLocations()->contains($location)) {
                        $manager->remove($location);
                    }
                }

                $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy(
                    ['supplier' => $supplier, 'profile' => $profile]
                );
                $supplierProfile->setStatus(SupplierProfile::STATUS_PENDING);
                foreach ($logEntries as $logEntry) {
                    $logEntry->setIsRead(true);
                }
                $manager->persist($supplier);
                $manager->flush();
                $this->sendProfileNotification($supplier, $profile);
                $this->addFlash('success', 'Location Created');

            } catch (\Exception $exception) {
                $this->addFlash('danger',
                    'Sorry we couldn’t find that address in Google Maps. Please try re-entering the address using a different format.');
            }

            return $this->redirectToRoute($profile->getRoute());
        }

        return $this->render('SupplierBundle:Profile:location.new.html.twig', [
            'form' => $form->createView(),
            'businessDays' => LocationTimetable::getBusinessDays(),
            'status' => $status,
            'profile' => $profile,
            'entries' => $entries,
            'supplier' => $supplier,
            'hasReplied' => $hasReplied,
            'hasRepliedCount' => $hasRepliedCount,
        ]);

    }

    /**
     * @Route("/customers", name="profile_customers")
     *
     * @param Request $request
     * @param         $mailer
     *
     * @return Response
     */
    public function customerAction(Request $request, Swift_Mailer $mailer)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $manager = $this->getDoctrine()->getManager();
        $profile = $supplier->getProfile(Profile::PROFILE_CUSTOMER);
        if (!$supplier->getCustomers()->count()) {
            $customer = new Customer();
            $supplier->addCustomer($customer);
        }
        $originalCustomers = new ArrayCollection();
        foreach ($supplier->getCustomers() as $customer) {
            $originalCustomers->add($customer);
        }

        $status = $supplier->getProfileStatus(Profile::PROFILE_CUSTOMER);
        $logEntries = $manager->getRepository('AppBundle:LogEntry')->findBy([
            'name' => Profile::PROFILE_CUSTOMER,
            'user' => $supplier->getUserIds(),
            'feedbackStatus' => [LogEntry::STATUS_FEEDBACK, LogEntry::STATUS_FEEDBACK_RESPONSE],
            'isRead' => false,
        ], ['loggedAt' => 'desc']);
        $hasRepliedCount = 0;
        foreach ($logEntries as $logEntry) {
            if ($logEntry->getFeedbackStatus() == LogEntry::STATUS_FEEDBACK_RESPONSE) {
                $hasRepliedCount++;
            }
        }
        $hasReplied = $hasRepliedCount == count($logEntries);
        $entries = $this
            ->get('activity_log.formatter')
            ->format($logEntries);
        $form = $this->createForm(SupplierCustomerType::class, $supplier);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalCustomers as $customer) {
                if (!$supplier->getCustomers()->contains($customer)) {
                    $manager->remove($customer);
                }
            }
            $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy([
                'supplier' => $supplier,
                'profile' => $profile,
            ]);
            $supplierProfile->setStatus(SupplierProfile::STATUS_PENDING);
            foreach ($logEntries as $logEntry) {
                $logEntry->setIsRead(true);
            }
            $manager->persist($supplier);
            $manager->flush();
            $this->sendProfileNotification($supplier, $profile);
            $this->addFlash('success', 'Customer Created');

            return $this->redirectToRoute($profile->getRoute());
        }

        return $this->render('SupplierBundle:Profile:customer.new.html.twig', [
            'form' => $form->createView(),
            'status' => $status,
            'profile' => $profile,
            'entries' => $entries,
            'supplier' => $supplier,
            'hasReplied' => $hasReplied,
            'hasRepliedCount' => $hasRepliedCount,

        ]);
    }

    /**
     * @Route("/experience", name="profile_experience")
     *
     * @param Request $request
     * @param         $mailer
     *
     * @return Response
     */
    public function experienceAction(Request $request, Swift_Mailer $mailer)
    {
        $manager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $profile = $supplier->getProfile(Profile::PROFILE_EXPERIENCE);
        /** @var User $user */
        if (!$supplier->getExperiences()->count()) {
            foreach (Service::getFunctions() as $function) {
                $experience = new Experience();
                $experience->setFunction($function);
                $supplier->addExperience($experience);
            }
        }
        $logEntries = $manager->getRepository('AppBundle:LogEntry')->findBy([
            'name' => Profile::PROFILE_EXPERIENCE,
            'user' => $supplier->getUserIds(),
            'feedbackStatus' => [LogEntry::STATUS_FEEDBACK, LogEntry::STATUS_FEEDBACK_RESPONSE],
            'isRead' => false,
        ], ['loggedAt' => 'desc']);

        $hasRepliedCount = 0;
        foreach ($logEntries as $logEntry) {
            if ($logEntry->getFeedbackStatus() == LogEntry::STATUS_FEEDBACK_RESPONSE) {
                $hasRepliedCount++;
            }
        }
        $hasReplied = $hasRepliedCount == count($logEntries);
        $entries = $this
            ->get('activity_log.formatter')
            ->format($logEntries);

        $form = $this->createForm(SupplierExperienceType::class, $supplier);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy([
                'supplier' => $supplier,
                'profile' => $profile,
            ]);
            $supplierProfile->setStatus(SupplierProfile::STATUS_PENDING);
            foreach ($logEntries as $logEntry) {
                $logEntry->setIsRead(true);
            }

            $manager->persist($supplier);
            $manager->flush();
            $this->sendProfileNotification($supplier, $profile);
            $this->addFlash('success', 'Experience Updated');

            return $this->redirectToRoute($profile->getRoute());
        }

        return $this->render('SupplierBundle:Profile:experience.html.twig', [
            'form' => $form->createView(),
            'functions' => Service::getFunctions(),
            'status' => $supplier->getProfileStatus(Profile::PROFILE_EXPERIENCE),
            'profile' => $profile,
            'entries' => $entries,
            'supplier' => $supplier,
            'hasReplied' => $hasReplied,
            'hasRepliedCount' => $hasRepliedCount,
        ]);
    }

    /**
     * @Route("/current-references", name="profile_current_reference")
     *
     * @param Request $request
     * @param         $mailer
     *
     * @return Response
     */
    public function currentReferencesAction(Request $request, Swift_Mailer $mailer)
    {
        $manager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $profile = $supplier->getProfile(Profile::PROFILE_CURRENT_REFERENCE);
        if (!$supplier->getReferencesByType(Reference::TYPE_CURRENT)->count()) {
            for ($i = 0; $i < 2; $i++) {
                $reference = new Reference();
                $reference->setType(Reference::TYPE_CURRENT);
                $supplier->addReference($reference);
            }
        }
        $logEntries = $manager->getRepository('AppBundle:LogEntry')->findBy([
            'name' => Profile::PROFILE_CURRENT_REFERENCE,
            'user' => $supplier->getUserIds(),
            'feedbackStatus' => [LogEntry::STATUS_FEEDBACK, LogEntry::STATUS_FEEDBACK_RESPONSE],
            'isRead' => false,
        ], ['loggedAt' => 'desc']);

        $hasRepliedCount = 0;
        foreach ($logEntries as $logEntry) {
            if ($logEntry->getFeedbackStatus() == LogEntry::STATUS_FEEDBACK_RESPONSE) {
                $hasRepliedCount++;
            }
        }
        $hasReplied = $hasRepliedCount == count($logEntries);

        $entries = $this
            ->get('activity_log.formatter')
            ->format($logEntries);
        $form = $this->createForm(SupplierCurrentReferenceType::class, $supplier);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy([
                'supplier' => $supplier,
                'profile' => $profile,
            ]);
            $supplierProfile->setStatus(SupplierProfile::STATUS_PENDING);
            foreach ($logEntries as $logEntry) {
                $logEntry->setIsRead(true);
            }
            $manager->persist($supplier);
            $manager->flush();
            $this->sendProfileNotification($supplier, $profile);
            $this->addFlash('success', 'Current reference update successfully');

            return $this->redirectToRoute($profile->getRoute());
        }

        return $this->render(
            'SupplierBundle:Profile:current-references.html.twig', [
            'form' => $form->createView(),
            'type' => Reference::TYPE_CURRENT,
            'status' => $supplier->getProfileStatus(Profile::PROFILE_CURRENT_REFERENCE),
            'profile' => $profile,
            'entries' => $entries,
            'supplier' => $supplier,
            'hasReplied' => $hasReplied,
            'hasRepliedCount' => $hasRepliedCount,
        ]);
    }

    /**
     * @Route("/past-references", name="profile_past_reference")
     *
     * @param Request $request
     * @param         $mailer
     *
     * @return Response
     */
    public function pastReferencesAction(Request $request, Swift_Mailer $mailer)
    {
        $manager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        if (!$supplier->getReferencesByType(Reference::TYPE_PAST)->count()) {
            for ($i = 0; $i < 2; $i++) {
                $reference = new Reference();
                $reference->setType(Reference::TYPE_PAST);
                $supplier->addReference($reference);
            }
        }
        $profile = $supplier->getProfile(Profile::PROFILE_PAST_REFERENCE);

        $logEntries = $manager->getRepository('AppBundle:LogEntry')->findBy([
            'name' => Profile::PROFILE_PAST_REFERENCE,
            'user' => $supplier->getUserIds(),
            'feedbackStatus' => [LogEntry::STATUS_FEEDBACK, LogEntry::STATUS_FEEDBACK_RESPONSE],
            'isRead' => false,
        ], ['loggedAt' => 'desc']);

        $hasRepliedCount = 0;
        foreach ($logEntries as $logEntry) {
            if ($logEntry->getFeedbackStatus() == LogEntry::STATUS_FEEDBACK_RESPONSE) {
                $hasRepliedCount++;
            }
        }
        $hasReplied = $hasRepliedCount == count($logEntries);

        $entries = $this
            ->get('activity_log.formatter')
            ->format($logEntries);

        $form = $this->createForm(SupplierPastReferenceType::class, $supplier);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy([
                'supplier' => $supplier,
                'profile' => $profile,
            ]);
            $supplierProfile->setStatus(SupplierProfile::STATUS_PENDING);
            foreach ($logEntries as $logEntry) {
                $logEntry->setIsRead(true);
            }
            $manager->persist($supplier);
            $manager->flush();
            $this->sendProfileNotification($supplier, $profile);
            $this->addFlash('success', 'Past reference update successfully');

            return $this->redirectToRoute($profile->getRoute());
        }

        return $this->render('SupplierBundle:Profile:past-references.html.twig', [
            'form' => $form->createView(),
            'type' => Reference::TYPE_PAST,
            'status' => $supplier->getProfileStatus(Profile::PROFILE_PAST_REFERENCE),
            'profile' => $profile,
            'entries' => $entries,
            'supplier' => $supplier,
            'hasReplied' => $hasReplied,
            'hasRepliedCount' => $hasRepliedCount,
        ]);
    }


    /**
     * @Route("/technology", name="profile_technology")
     *
     * @param Request $request
     * @param         $mailer
     *
     * @return Response
     */
    public function technologyAction(Request $request, Swift_Mailer $mailer)
    {
        $manager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $profile = $supplier->getProfile(Profile::PROFILE_TECHNOLOGY);
        if (!$supplier->getTechnologies()->count()) {
            foreach (Technology::getTechnologies($supplier) as $technology) {
                $tech = new Technology();
                $tech->setTechnology($technology);
                $tech->setType(Technology::TYPE_DEFAULT);
                $supplier->addTechnology($tech);
            }
        }

        $originalTechnologies = new ArrayCollection();
        foreach ($supplier->getTechnologies() as $technology) {
            $originalTechnologies->add($technology);
        }

        $logEntries = $manager->getRepository('AppBundle:LogEntry')->findBy([
            'name' => Profile::PROFILE_TECHNOLOGY,
            'user' => $supplier->getUserIds(),
            'feedbackStatus' => [LogEntry::STATUS_FEEDBACK, LogEntry::STATUS_FEEDBACK_RESPONSE],
            'isRead' => false,
        ], ['loggedAt' => 'desc']);

        $hasRepliedCount = 0;
        foreach ($logEntries as $logEntry) {
            if ($logEntry->getFeedbackStatus() == LogEntry::STATUS_FEEDBACK_RESPONSE) {
                $hasRepliedCount++;
            }
        }
        $hasReplied = $hasRepliedCount == count($logEntries);

        $entries = $this
            ->get('activity_log.formatter')
            ->format($logEntries);
        $form = $this->createForm(SupplierTechnologyType::class, $supplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalTechnologies as $technology) {
                if (!$supplier->getTechnologies()->contains($technology)) {
                    $manager->remove($technology);
                }
            }
            $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy([
                'supplier' => $supplier,
                'profile' => $profile,
            ]);
            $supplierProfile->setStatus(SupplierProfile::STATUS_PENDING);
            foreach ($logEntries as $logEntry) {
                $logEntry->setIsRead(true);
            }
            $manager->persist($supplier);
            $manager->flush();
            $this->sendProfileNotification($supplier, $profile);
            $this->addFlash('success', 'Technology updated successfully');

            return $this->redirectToRoute($profile->getRoute());
        }

        return $this->render('SupplierBundle:Profile:technology.html.twig', [
            'form' => $form->createView(),
            'technologies' => Technology::getTechnologies($supplier),
            'status' => $supplier->getProfileStatus(Profile::PROFILE_TECHNOLOGY),
            'profile' => $profile,
            'entries' => $entries,
            'supplier' => $supplier,
            'hasReplied' => $hasReplied,
            'hasRepliedCount' => $hasRepliedCount,
        ]);
    }

    /**
     *
     * @Route("/channel-support", name="profile_channel_support")
     * @param Request $request
     * @param         $mailer
     *
     * @return Response
     */
    public function channelSupportAction(Request $request, Swift_Mailer $mailer)
    {
        $manager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $profile = $supplier->getProfile(Profile::PROFILE_CHANNEL_SUPPORT);
        if (!$supplier->getChannelSupports()->count()) {
            foreach (ChannelSupport::getChannels() as $channel) {
                $channelSupport = new ChannelSupport();
                $channelSupport->setChannel($channel);
                $channelSupport->setType(ChannelSupport::TYPE_DEFAULT);
                $supplier->addChannelSupport($channelSupport);
            }
        }

        $originalChannels = new ArrayCollection();
        foreach ($supplier->getChannelSupports() as $channelSupport) {
            $originalChannels->add($channelSupport);
        }

        $logEntries = $manager->getRepository('AppBundle:LogEntry')->findBy([
            'name' => Profile::PROFILE_CHANNEL_SUPPORT,
            'user' => $supplier->getUserIds(),
            'feedbackStatus' => [LogEntry::STATUS_FEEDBACK, LogEntry::STATUS_FEEDBACK_RESPONSE],
            'isRead' => false,
        ], ['loggedAt' => 'desc']);

        $hasRepliedCount = 0;
        foreach ($logEntries as $logEntry) {
            if ($logEntry->getFeedbackStatus() == LogEntry::STATUS_FEEDBACK_RESPONSE) {
                $hasRepliedCount++;
            }
        }
        $hasReplied = $hasRepliedCount == count($logEntries);

        $entries = $this
            ->get('activity_log.formatter')
            ->format($logEntries);
        $form = $this->createForm(SupplierChannelSupportType::class, $supplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalChannels as $channel) {
                if (!$supplier->getChannelSupports()->contains($channel)) {
                    $manager->remove($channel);
                }
            }
            $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy([
                'supplier' => $supplier,
                'profile' => $profile,
            ]);
            $supplierProfile->setStatus(SupplierProfile::STATUS_PENDING);
            foreach ($logEntries as $logEntry) {
                $logEntry->setIsRead(true);
            }
            $manager->persist($supplier);
            $manager->flush();
            $this->sendProfileNotification($supplier, $profile);
            $this->addFlash('success', 'Channel Support updated successfully');

            return $this->redirectToRoute($profile->getRoute());
        }

        return $this->render('SupplierBundle:Profile:channel-support.html.twig', [
            'form' => $form->createView(),
            'channels' => ChannelSupport::getChannels(),
            'status' => $supplier->getProfileStatus(Profile::PROFILE_CHANNEL_SUPPORT),
            'profile' => $profile,
            'entries' => $entries,
            'supplier' => $supplier,
            'hasReplied' => $hasReplied,
            'hasRepliedCount' => $hasRepliedCount,
        ]);
    }

    /**
     * @Route("/award", name="profile_award")
     *
     * @param Request $request
     * @param         $mailer
     *
     * @return Response
     */
    public function awardAction(Request $request, Swift_Mailer $mailer)
    {
        $manager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $profile = $supplier->getProfile(Profile::PROFILE_AWARD);
        $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy([
            'supplier' => $supplier,
            'profile' => $profile,
        ]);
        $originalAwards = new ArrayCollection();
        foreach ($supplier->getAwards() as $award) {
            $originalAwards->add($award);
        }

        $logEntries = $manager->getRepository('AppBundle:LogEntry')->findBy([
            'name' => Profile::PROFILE_AWARD,
            'user' => $supplier->getUserIds(),
            'feedbackStatus' => [LogEntry::STATUS_FEEDBACK, LogEntry::STATUS_FEEDBACK_RESPONSE],
            'isRead' => false,
        ], ['loggedAt' => 'desc']);

        $hasRepliedCount = 0;
        foreach ($logEntries as $logEntry) {
            if ($logEntry->getFeedbackStatus() == LogEntry::STATUS_FEEDBACK_RESPONSE) {
                $hasRepliedCount++;
            }
        }
        $hasReplied = $hasRepliedCount == count($logEntries);

        $entries = $this
            ->get('activity_log.formatter')
            ->format($logEntries);
        $form = $this->createForm(SupplierAwardType::class, $supplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalAwards as $award) {
                if (!$supplier->getAwards()->contains($award)) {
                    $manager->remove($award);
                }
            }
            $supplierProfile->setStatus(SupplierProfile::STATUS_PENDING);
            foreach ($logEntries as $logEntry) {
                $logEntry->setIsRead(true);
            }
            $manager->persist($supplier);
            $manager->flush();
            $this->sendProfileNotification($supplier, $profile);
            $this->addFlash('success', 'Award update successfully');

            return $this->redirectToRoute($profile->getRoute());
        }

        //$supplierProfileForm = $this->createForm(SupplierProfileType::class, $supplierProfile);

        return $this->render('SupplierBundle:Profile:award.html.twig', [
            'form' => $form->createView(),
            'status' => $supplier->getProfileStatus(Profile::PROFILE_AWARD),
            'profile' => $profile,
            'entries' => $entries,
            'supplier' => $supplier,
            //'supplierProfileForm' => $supplierProfileForm->createView(),
            'hasReplied' => $hasReplied,
            'hasRepliedCount' => $hasRepliedCount,
        ]);
    }

    /**
     * @Route("/support-functions", name="profile_support")
     *
     * @param Request $request
     * @param         $mailer
     *
     * @return Response
     */
    public function supportAction(Request $request, Swift_Mailer $mailer)
    {
        $manager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $profile = $supplier->getProfile(Profile::PROFILE_SUPPORT_FUNCTION);
        if (!$supplier->getSupportFunctions()->count()) {
            foreach (SupportFunction::getFunctions() as $function) {
                $support = new SupportFunction();
                $support->setFunction($function);
                $supplier->addSupportFunction($support);
            }
        }
        $logEntries = $manager->getRepository('AppBundle:LogEntry')->findBy([
            'name' => Profile::PROFILE_SUPPORT_FUNCTION,
            'user' => $supplier->getUserIds(),
            'feedbackStatus' => [LogEntry::STATUS_FEEDBACK, LogEntry::STATUS_FEEDBACK_RESPONSE],
            'isRead' => false,
        ], ['loggedAt' => 'desc']);

        $hasRepliedCount = 0;
        foreach ($logEntries as $logEntry) {
            if ($logEntry->getFeedbackStatus() == LogEntry::STATUS_FEEDBACK_RESPONSE) {
                $hasRepliedCount++;
            }
        }
        $hasReplied = $hasRepliedCount == count($logEntries);

        $entries = $this
            ->get('activity_log.formatter')
            ->format($logEntries);
        $form = $this->createForm(SupplierSupportFunctionType::class, $supplier);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy([
                'supplier' => $supplier,
                'profile' => $profile,
            ]);
            $supplierProfile->setStatus(SupplierProfile::STATUS_PENDING);
            foreach ($logEntries as $logEntry) {
                $logEntry->setIsRead(true);
            }

            $manager->persist($supplier);
            $manager->flush();
            $this->sendProfileNotification($supplier, $profile);
            $this->addFlash('success', 'Support Function update successfully');

            return $this->redirectToRoute($profile->getRoute());
        }

        return $this->render('SupplierBundle:Profile:support.html.twig', [
            'form' => $form->createView(),
            'functions' => SupportFunction::getFunctions(),
            'status' => $supplier->getProfileStatus(Profile::PROFILE_SUPPORT_FUNCTION),
            'profile' => $profile,
            'entries' => $entries,
            'supplier' => $supplier,
            'hasReplied' => $hasReplied,
            'hasRepliedCount' => $hasRepliedCount,
        ]);
    }

    /**
     * @Route("/legals", name="profile_legals")
     *
     * @param Request $request
     * @param         $mailer
     *
     * @return Response
     */
    public function legalAction(Request $request, Swift_Mailer $mailer)
    {
        $manager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $profile = $supplier->getProfile(Profile::PROFILE_LEGAL);


        $originalCertifications = new ArrayCollection();

        foreach ($supplier->getCertifications() as $certification) {
            $originalCertifications->add($certification);
        }

        $logEntries = $manager->getRepository('AppBundle:LogEntry')->findBy([
            'name' => Profile::PROFILE_LEGAL,
            'user' => $supplier->getUserIds(),
            'feedbackStatus' => [LogEntry::STATUS_FEEDBACK, LogEntry::STATUS_FEEDBACK_RESPONSE],
            'isRead' => false,
        ], ['loggedAt' => 'desc']);

        $hasRepliedCount = 0;
        foreach ($logEntries as $logEntry) {
            if ($logEntry->getFeedbackStatus() == LogEntry::STATUS_FEEDBACK_RESPONSE) {
                $hasRepliedCount++;
            }
        }
        $hasReplied = $hasRepliedCount == count($logEntries);

        $entries = $this
            ->get('activity_log.formatter')
            ->format($logEntries);

        $form = $this->createForm(SupplierCertificationType::class, $supplier);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalCertifications as $certification) {
                if (!$supplier->getCertifications()->contains($certification)) {
                    $manager->remove($certification);
                }
            }
            $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy([
                'supplier' => $supplier,
                'profile' => $profile,
            ]);
            $supplierProfile->setStatus(SupplierProfile::STATUS_PENDING);
            foreach ($logEntries as $logEntry) {
                $logEntry->setIsRead(true);
            }
            $manager->persist($supplier);
            $manager->flush();
            $this->sendProfileNotification($supplier, $profile);
            $this->addFlash('success', 'Legal update successfully');

            return $this->redirectToRoute($profile->getRoute());
        }

        return $this->render('SupplierBundle:Profile:legal.html.twig', [
            'form' => $form->createView(),
            'status' => $supplier->getProfileStatus(Profile::PROFILE_LEGAL),
            'profile' => $profile,
            'entries' => $entries,
            'supplier' => $supplier,
            'hasReplied' => $hasReplied,
            'hasRepliedCount' => $hasRepliedCount,
        ]);
    }

    /**
     * @Route("/commercials", name="profile_commercial")
     *
     * @param Request $request
     * @param         $mailer
     *
     * @return Response
     */
    public function commercialAction(Request $request, Swift_Mailer $mailer)
    {
        $manager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $profile = $supplier->getProfile(Profile::PROFILE_COMMERCIAL);

        if (!$commercial = $supplier->getCommercial()) {
            $commercial = new Commercial();
            $commercial->setSupplier($supplier);
        }

        $logEntries = $manager->getRepository('AppBundle:LogEntry')->findBy([
            'name' => Profile::PROFILE_COMMERCIAL,
            'user' => $supplier->getUserIds(),
            'feedbackStatus' => [LogEntry::STATUS_FEEDBACK, LogEntry::STATUS_FEEDBACK_RESPONSE],
            'isRead' => false,
        ], ['loggedAt' => 'desc']);

        $hasRepliedCount = 0;
        foreach ($logEntries as $logEntry) {
            if ($logEntry->getFeedbackStatus() == LogEntry::STATUS_FEEDBACK_RESPONSE) {
                $hasRepliedCount++;
            }
        }
        $hasReplied = $hasRepliedCount == count($logEntries);

        $entries = $this
            ->get('activity_log.formatter')
            ->format($logEntries);
        $form = $this->createForm(CommercialType::class, $commercial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy([
                'supplier' => $supplier,
                'profile' => $profile,
            ]);
            $supplierProfile->setStatus(SupplierProfile::STATUS_PENDING);
            foreach ($logEntries as $logEntry) {
                $logEntry->setIsRead(true);
            }
            $manager->persist($commercial);
            $manager->flush();
            $this->sendProfileNotification($supplier, $profile);
            $this->addFlash('success', 'Commercials update successfully');

            return $this->redirectToRoute($profile->getRoute());
        }

        return $this->render('SupplierBundle:Profile:commercial.html.twig', [
            'form' => $form->createView(),
            'status' => $supplier->getProfileStatus(Profile::PROFILE_COMMERCIAL),
            'profile' => $profile,
            'entries' => $entries,
            'supplier' => $supplier,
            'hasReplied' => $hasReplied,
            'hasRepliedCount' => $hasRepliedCount,
        ]);
    }

    /**
     * @Route("/minimum-volumes",name="profile_volume")
     *
     * @param Request $request
     * @param         $mailer
     *
     * @return Response
     */
    public function volumeAction(Request $request, Swift_Mailer $mailer)
    {
        $manager = $this->getDoctrine()->getManager();
        $profile = $manager->getRepository('AppBundle:Profile')->findOneBy(['name' => Profile::PROFILE_MINIMUM_VOLUME]);
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        if (!$supplier->getMinimumVolume()) {
            $volume = new MinimumVolume();
        } else {
            $volume = $supplier->getMinimumVolume();
        }

        $logEntries = $manager->getRepository('AppBundle:LogEntry')->findBy([
            'name' => Profile::PROFILE_MINIMUM_VOLUME,
            'user' => $supplier->getUserIds(),
            'feedbackStatus' => LogEntry::STATUS_FEEDBACK,
            'isRead' => false,
        ], ['loggedAt' => 'desc']);

        $hasRepliedCount = 0;
        foreach ($logEntries as $logEntry) {
            if ($logEntry->getFeedbackStatus() == LogEntry::STATUS_FEEDBACK_RESPONSE) {
                $hasRepliedCount++;
            }
        }
        $hasReplied = $hasRepliedCount == count($logEntries);

        $entries = $this
            ->get('activity_log.formatter')
            ->format($logEntries);
        $form = $this->createForm(MinimumVolumeType::class, $volume);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $volume->setSupplier($supplier);
            $manager->persist($volume);
            $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy([
                'supplier' => $supplier,
                'profile' => $profile,
            ]);
            $supplierProfile->setStatus(SupplierProfile::STATUS_PENDING);
            foreach ($logEntries as $logEntry) {
                $logEntry->setIsRead(true);
            }
            $manager->flush();
            $this->sendProfileNotification($supplier, $profile);
            $this->addFlash('success', 'Minimum Volume saved');

            return $this->redirectToRoute($profile->getRoute());
        }

        return $this->render('SupplierBundle:Profile:volume.html.twig', [
            'form' => $form->createView(),
            'status' => $supplier->getProfileStatus(Profile::PROFILE_MINIMUM_VOLUME),
            'profile' => $profile,
            'entries' => $entries,
            'supplier' => $supplier,
            'hasReplied' => $hasReplied,
            'hasRepliedCount' => $hasRepliedCount,
        ]);
    }

    /**
     * @Route("/entry/{entryId}/reply", name="profile_entry_feedback")
     *
     * @param $request
     * @param $entryId
     *
     * @return string
     */
    public function replyAction(Request $request, $entryId)
    {

        $manager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $entry = $manager->getRepository('AppBundle:LogEntry')->find($entryId);
        $form = $this
            ->createFormBuilder()
            ->setAction($this->generateUrl('profile_entry_feedback', ['entryId' => $entryId]))
            ->add(
                'reply',
                TextareaType::class,
                [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Please add you reply',
                        'rows' => 10,
                        'class' => 'resize-vertically',
                    ],
                ]
            )
            ->getForm();

        $form->handleRequest($request);
        $profile = $manager->getRepository('AppBundle:Profile')->findOneBy(['name' => $entry->getName()]);
        if ($form->isSubmitted() && $form->isValid()) {
            $originalReply = $entry->getSupplierReply();
            $reply = $form->getData()['reply'];
            $dateString = (new \DateTime('now', new \DateTimeZone('Australia/Melbourne')))->format('Y-m-d H:i:s');
            $newReply = $originalReply . "($dateString) $reply<br />";
            $entry->setSupplierReply($newReply);
            $entry->setFeedbackStatus(LogEntry::STATUS_FEEDBACK_RESPONSE);
            $manager->flush();
            $this->addFlash('success', 'Reply added');

            return $this->redirectToRoute($profile->getRoute());
        }

        return $this->render('form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/{profileId}/send-feedback", name="profile_send_feedback")
     *
     * @param $profileId
     *
     * @return Response
     */
    public function sendFeedbackAction($profileId)
    {
        $manager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $profile = $manager->getRepository('AppBundle:Profile')->find($profileId);

        $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy([
            'profile' => $profile,
            'supplier' => $user->getSupplier(),
        ]);

        $supplierProfile->setStatus(SupplierProfile::STATUS_PENDING);
        $entries = $manager->getRepository('AppBundle:LogEntry')->findBy([
            'name' => Profile::PROFILE_CONTACT,
            'user' => $user->getSupplier()->getUserIds(),
            'feedbackStatus' => [LogEntry::STATUS_FEEDBACK, LogEntry::STATUS_FEEDBACK_RESPONSE],
            'isRead' => false,
        ], ['loggedAt' => 'desc']);

        foreach ($entries as $entry) {
            $entry->setIsRead(true);
        }

        $manager->flush();

        return $this->redirectToRoute($profile->getRoute());
    }

    /**
     * @Route("/entry/{entryId}/read", name="profile_entry_read")
     *
     * @param $entryId
     *
     * @return Response
     */
    public function readAction($entryId)
    {
        $manager = $this->getDoctrine()->getManager();

        $entry = $manager->getRepository('AppBundle:LogEntry')->find($entryId);

        $profile = $manager->getRepository('AppBundle:Profile')->findOneBy(['name' => $entry->getName()]);

        $entry->setIsRead(true);
        $manager->persist($entry);
        $manager->flush();

        return $this->redirectToRoute($profile->getRoute());
    }

    /**
     * @Route("/{id}/available/{status}", name="profile_available")
     *
     * @param  $id
     * @param  $status
     *
     * @return JsonResponse
     */
    public function profileAvailableAction($id, $status)
    {
        $manager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $profile = $manager->getRepository('AppBundle:Profile')->find($id);

        $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy([
            'supplier' => $supplier,
            'profile' => $profile,
        ]);
        if ($status == 'true') {
            $supplierProfile->setIsDisabled(false);
        } elseif ($status == 'false') {
            $supplierProfile->setIsDisabled(true);
        }

        if (!$supplierProfile->getIsDisabled()) {
            $awards = $manager->getRepository('AppBundle:Award')->findBy(['name' => 'Nil']);
            $ids = [];
            foreach ($awards as $award) {
                $ids[] = $award->getId();
                $manager->remove($award);
            }
            $manager->flush();
            $entries = $manager->getRepository('AppBundle:LogEntry')->findBy(['objectId' => $ids]);
            foreach ($entries as $entry) {
                $manager->remove($entry);
            }
        }
        $manager->persist($supplierProfile);
        $manager->flush();

        return new JsonResponse(['isDisabled' => $supplierProfile->getIsDisabled()]);
    }

    /**
     * @Route("/data-acquisition", name="profile_data_acquisition")
     *
     * @param Request $request
     * @param         $mailer
     *
     * @return Response
     */
    public function dataAcquisitionAction(Request $request, Swift_Mailer $mailer)
    {
        $manager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $profile = $supplier->getProfile(Profile::PROFILE_DATA_ACQUISITION);
        if (!$supplier->getDataAcquisition()) {
            $dataAcquisition = new DataAcquisition();
        } else {
            $dataAcquisition = $supplier->getDataAcquisition();
        }

        $logEntries = $manager->getRepository('AppBundle:LogEntry')->findBy([
            'name' => Profile::PROFILE_DATA_ACQUISITION,
            'user' => $supplier->getUserIds(),
            'feedbackStatus' => LogEntry::STATUS_FEEDBACK,
            'isRead' => false,
        ], ['loggedAt' => 'desc']);

        $hasRepliedCount = 0;
        foreach ($logEntries as $logEntry) {
            if ($logEntry->getFeedbackStatus() == LogEntry::STATUS_FEEDBACK_RESPONSE) {
                $hasRepliedCount++;
            }
        }
        $hasReplied = $hasRepliedCount == count($logEntries);

        $entries = $this
            ->get('activity_log.formatter')
            ->format($logEntries);
        $form = $this->createForm(DataAcquisitionType::class, $dataAcquisition);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dataAcquisition->setSupplier($supplier);
            if ($supplier->isVirtualAssistant()) {
                $providerProfile = $supplier->getProfile(Profile::PROFILE_DATA_ACQUISITION_PROVIDER);
                $dataAcquisitionProvider = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy([
                    'supplier' => $supplier,
                    'profile' => $providerProfile,
                ]);
                if ($dataAcquisition->requireProviders()) {
                    $dataAcquisitionProvider->setStatus(SupplierProfile::STATUS_INCOMPLETE);
                } else {
                    $dataAcquisitionProvider->setStatus(SupplierProfile::STATUS_OPTIONAL);
                }
            }
            $manager->persist($dataAcquisition);
            $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy([
                'supplier' => $supplier,
                'profile' => $profile,
            ]);
            $supplierProfile->setStatus(SupplierProfile::STATUS_PENDING);
            foreach ($logEntries as $logEntry) {
                $logEntry->setIsRead(true);
            }
            $manager->flush();
            $this->sendProfileNotification($supplier, $profile);
            $this->addFlash('success', 'Data Acquisition saved');

            return $this->redirectToRoute($profile->getRoute());
        }

        return $this->render('SupplierBundle:Profile:data-acquisition.html.twig', [
            'form' => $form->createView(),
            'status' => $supplier->getProfileStatus(Profile::PROFILE_DATA_ACQUISITION),
            'profile' => $profile,
            'entries' => $entries,
            'supplier' => $supplier,
            'hasReplied' => $hasReplied,
            'hasRepliedCount' => $hasRepliedCount,
        ]);
    }

    /**
     * @Route("/data-acquisition-provider", name="profile_data_acquisition_provider")
     *
     * @param Request $request
     * @param         $mailer
     *
     * @return Response
     */
    public function dataAcquisitionProviderAction(Request $request, Swift_Mailer $mailer)
    {
        $manager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $profile = $supplier->getProfile(Profile::PROFILE_DATA_ACQUISITION_PROVIDER);
        $originalDataAcquisitionProviders = new ArrayCollection();
        foreach ($supplier->getDataAcquisitionProviders() as $acquisitionProvider) {
            $originalDataAcquisitionProviders->add($acquisitionProvider);
        }

        $logEntries = $manager->getRepository('AppBundle:LogEntry')->findBy([
            'name' => Profile::PROFILE_DATA_ACQUISITION_PROVIDER,
            'user' => $supplier->getUserIds(),
            'feedbackStatus' => LogEntry::STATUS_FEEDBACK,
            'isRead' => false,
        ], ['loggedAt' => 'desc']);

        $hasRepliedCount = 0;
        foreach ($logEntries as $logEntry) {
            if ($logEntry->getFeedbackStatus() == LogEntry::STATUS_FEEDBACK_RESPONSE) {
                $hasRepliedCount++;
            }
        }
        $hasReplied = $hasRepliedCount == count($logEntries);

        $entries = $this
            ->get('activity_log.formatter')
            ->format($logEntries);
        $form = $this->createForm(SupplierDataAcquisitionProviderType::class, $supplier);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalDataAcquisitionProviders as $acquisitionProvider) {
                if (!$supplier->getDataAcquisitionProviders()->contains($acquisitionProvider)) {
                    $manager->remove($acquisitionProvider);
                }
            }
            $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy([
                'supplier' => $supplier,
                'profile' => $profile,
            ]);
            $supplierProfile->setStatus(SupplierProfile::STATUS_PENDING);
            foreach ($logEntries as $logEntry) {
                $logEntry->setIsRead(true);
            }
            $manager->persist($supplier);
            $manager->flush();
            $this->sendProfileNotification($supplier, $profile);
            $this->addFlash('success', 'Data Acquisition Provider saved');

            return $this->redirectToRoute($profile->getRoute());
        }

        return $this->render('SupplierBundle:Profile:data-acquisition-provider.html.twig', [
            'form' => $form->createView(),
            'status' => $supplier->getProfileStatus(Profile::PROFILE_DATA_ACQUISITION_PROVIDER),
            'profile' => $profile,
            'entries' => $entries,
            'supplier' => $supplier,
            'hasReplied' => $hasReplied,
            'hasRepliedCount' => $hasRepliedCount,
        ]);
    }

    /**
     * @Route("/tenders", name="profile_tenders")
     *
     * @param Request      $request
     * @param Swift_Mailer $mailer
     *
     * @return Response
     */
    public function tenderAction(Request $request, Swift_Mailer $mailer)
    {
        $manager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $profile = $supplier->getProfile(Profile::PROFILE_TENDER);

        if (!$supplier->getTender()) {
            $tender = new Tender();
        } else {
            $tender = $supplier->getTender();
        }

        $logEntries = $manager->getRepository('AppBundle:LogEntry')->findBy([
            'name' => Profile::PROFILE_TENDER,
            'user' => $supplier->getUserIds(),
            'feedbackStatus' => LogEntry::STATUS_FEEDBACK,
            'isRead' => false,
        ], ['loggedAt' => 'desc']);

        $hasRepliedCount = 0;
        foreach ($logEntries as $logEntry) {
            if ($logEntry->getFeedbackStatus() == LogEntry::STATUS_FEEDBACK_RESPONSE) {
                $hasRepliedCount++;
            }
        }
        $hasReplied = $hasRepliedCount == count($logEntries);

        $entries = $this
            ->get('activity_log.formatter')
            ->format($logEntries);
        $form = $this->createForm(TenderType::class, $tender);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tender->setSupplier($supplier);
            $manager->persist($tender);
            $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy([
                'supplier' => $supplier,
                'profile' => $profile,
            ]);
            $supplierProfile->setStatus(SupplierProfile::STATUS_PENDING);
            foreach ($logEntries as $logEntry) {
                $logEntry->setIsRead(true);
            }
            $manager->flush();
            $this->sendProfileNotification($supplier, $profile);
            $this->addFlash('success', 'Tenders saved');

            return $this->redirectToRoute($profile->getRoute());
        }

        return $this->render('@Supplier/Profile/tenders.html.twig', [
            'form' => $form->createView(),
            'status' => $supplier->getProfileStatus(Profile::PROFILE_TENDER),
            'profile' => $profile,
            'entries' => $entries,
            'supplier' => $supplier,
            'hasReplied' => $hasReplied,
            'hasRepliedCount' => $hasRepliedCount,
        ]);
    }

    /**
     * @param Request      $request
     * @param Swift_Mailer $mailer
     *
     * @Route("/work-from-home", name="profile_workfromhome")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function workFromHomeAction(Request $request, Swift_Mailer $mailer)
    {
        $manager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $profile = $supplier->getProfile(Profile::PROFILE_WORK_FROM_HOME);
        $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy([
            'supplier' => $supplier,
            'profile' => $profile,
        ]);

        if (!$supplier->getWorkFromHomes()->count()) {
            $supplier->addWorkFromHome(new WorkFromHome());
        }

        $originalWorkFromHomes = new ArrayCollection();
        foreach ($supplier->getWorkFromHomes() as $workFromHome) {
            $originalWorkFromHomes->add($workFromHome);
        }

        $logEntries = $manager->getRepository('AppBundle:LogEntry')->findBy([
            'name' => Profile::PROFILE_WORK_FROM_HOME,
            'user' => $supplier->getUserIds(),
            'feedbackStatus' => LogEntry::STATUS_FEEDBACK,
            'isRead' => false,
        ], ['loggedAt' => 'desc']);

        $hasRepliedCount = 0;
        foreach ($logEntries as $logEntry) {
            if ($logEntry->getFeedbackStatus() == LogEntry::STATUS_FEEDBACK_RESPONSE) {
                $hasRepliedCount++;
            }
        }
        $hasReplied = $hasRepliedCount == count($logEntries);

        $entries = $this
            ->get('activity_log.formatter')
            ->format($logEntries);
        $form = $this->createForm(SupplierWorkFromHomeType::class, $supplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                foreach ($originalWorkFromHomes as $workFromHome) {
                    if (!$supplier->getWorkFromHomes()->contains($workFromHome)) {
                        $manager->remove($workFromHome);
                    }
                }
                $supplierProfile->setStatus(SupplierProfile::STATUS_PENDING);
                foreach ($logEntries as $logEntry) {
                    $logEntry->setIsRead(true);
                }
                $manager->persist($supplier);
                $manager->flush();
                $this->sendProfileNotification($supplier, $profile);
                $this->addFlash('success', 'Work From Home saved');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }

            return $this->redirectToRoute($profile->getRoute());
        }

        $supplierProfileForm = $this->createForm(SupplierProfileType::class, $supplierProfile);

        return $this->render(
            '@Supplier/Profile/workformhome.html.twig', [
            'form' => $form->createView(),
            'status' => $supplier->getProfileStatus(Profile::PROFILE_WORK_FROM_HOME),
            'profile' => $profile,
            'entries' => $entries,
            'supplier' => $supplier,
            'hasReplied' => $hasReplied,
            'hasRepliedCount' => $hasRepliedCount,
            'supplierProfileForm' => $supplierProfileForm->createView(),
        ]);
    }

    private function sendProfileNotification($supplier, $profile)
    {
        /** @var User $user */
        $user = $this->getUser();
        $message = (new \Swift_Message('New Profile Pending Approval'))
            ->setFrom('admin@cxconnect.com.au', 'CX Connect')
            ->setTo('admin@cxconnect.com.au')
            ->setBody(
                $this->renderView(
                    'emails/supplier/profile.html.twig',
                    ['supplier' => $supplier, 'profile' => $profile]
                ),
                'text/html'
            );

        $this->get('mailer')->send($message);
    }
}
