<?php

namespace SupplierBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\CampaignType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class CampaignController
 *
 * @package SupplierBundle\Controller
 *
 * @Route("/campaigns")
 */
class CampaignController extends Controller
{

    /**
     * @Route("", name="campaign_view")
     */
    public function campaignsAction()
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $supplier = $user->getSupplier();

        return $this->render('SupplierBundle:Campaign:campaign.html.twig', [
           'supplier' => $supplier
        ]);
    }

    /**
     * @Route("/{id}/edit", name="campaign_edit")
     *
     * @param $request
     * @param $id
     *
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();

        $manager = $this->getDoctrine()->getManager();

        $campaign = $manager->getRepository('AppBundle:LeadSupplier')->findOneBy(['id' => $id, 'supplier' => $supplier]);

        if (!$campaign) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(CampaignType::class, $campaign);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($campaign);
            $manager->flush();
            $this->addFlash('success', 'Campaign updated');
            return $this->redirectToRoute('campaign_view');
        }

        return $this->render('@Supplier/Campaign/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
