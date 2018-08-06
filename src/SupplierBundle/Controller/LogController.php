<?php

namespace SupplierBundle\Controller;

use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class LogController extends Controller
{
    /**
     * @Route("/profile/{id}/log", name="log_profile")
     *
     * @param $id
     *
     * @return Response
     */
    public function profileLogAction($id)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $manager = $this->getDoctrine()->getManager();
        $profile = $manager->getRepository('AppBundle:Profile')->find($id);

        $supplierProfile = $manager->getRepository('AppBundle:SupplierProfile')->findOneBy(['supplier' => $supplier, 'profile'=> $profile]);

        $entries = $manager
            ->getRepository('AppBundle:LogEntry')
            ->findBy(['name' => Profile::getProfile($profile->getSlug()), 'user' => $supplier->getUserIds()], ['loggedAt' => 'desc']);

        $entries = $this
            ->get('activity_log.formatter')
            ->format($entries);

        return $this->render('SupplierBundle:Log:profile_log.html.twig', compact('entries', 'supplier', 'profile', 'supplierProfile'));
    }

    /**
     * @Route("/change-logs", name="log_all")
     */
    public function logsAction()
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $manager = $this->getDoctrine()->getManager();

        $entries = $manager
            ->getRepository('AppBundle:LogEntry')
            ->findBy(['user' => $supplier->getUserIds()], ['loggedAt' => 'desc']);

        $entries = $this
            ->get('activity_log.formatter')
            ->format($entries);

        return $this->render('SupplierBundle:Log:all.html.twig', compact('entries', 'supplier', 'profile', 'supplierProfile'));

    }

}
