<?php

namespace AppBundle\Controller;

use AppBundle\Entity\LeadSupplier;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class CampaignController
 *
 * @package AppBundle\Controller
 * @Route("/campaigns")
 */
class CampaignController extends Controller
{
    /**
     * @Route("/", name="campaign_all")
     */
    public function allAction()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $manager = $this->getDoctrine()->getManager();

        $campaigns = $manager->getRepository('AppBundle:LeadSupplier')->findBy(['result' => LeadSupplier::RESULT_SUCCESS]);


        return $this->render('AppBundle:Campaign:all.html.twig', compact('campaigns'));
    }

}
