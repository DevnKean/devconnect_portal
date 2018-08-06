<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class LogController extends Controller
{
    /**
     * @Route("/profileLog")
     */
    public function profileLogAction()
    {
        return $this->render('AppBundle:Log:profile_log.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/logs", name="logs")
     */
    public function logsAction()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();

        $entries = $manager
            ->getRepository('AppBundle:LogEntry')
            ->findBy([], ['loggedAt' => 'desc']);

        $entries = $this
            ->get('activity_log.formatter')
            ->format($entries);

        return $this->render('AppBundle:Log:logs.html.twig', compact('entries', 'supplier', 'profile', 'supplierProfile'));
    }

}
