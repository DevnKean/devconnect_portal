<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ReportController
 *
 * @package AppBundle\Controller
 * @Route("/report")
 */
class ReportController extends Controller
{
    /**
     * @Route("/lead/{id}/tracker", name="report_lead_tracker")
     *
     * @param $id
     * @return Response
     */
    public function leadTrackerAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $manager = $this->getDoctrine()->getManager();
        $lead = $manager->find('AppBundle:Lead', $id);

        if (!$lead) {
            throw new NotFoundHttpException();
        }
        $trackerReport = $lead->getTrackerReport();
        return $this->render('AppBundle:Report:lead_tracker.html.twig', array(
           'trackers' => $trackerReport
        ));
    }

    /**
     * @Route("lead-status-tracker", name="report_lead_status_tracker")
     */
    public function leadStatusTrackerAction()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $manager = $this->getDoctrine()->getManager();

        $leads = $manager->getRepository('AppBundle:Lead')->findAll();

        foreach ($leads as $lead) {

        }

        return $this->render('AppBundle:Report:lead_tracker.html.twig', array(
            'trackers' => $trackerReport
        ));
    }

}
