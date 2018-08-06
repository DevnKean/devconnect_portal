<?php

namespace SupplierBundle\Controller;

use AppBundle\Entity\SupplierProfile;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DashboardController extends Controller
{
    /**
     * @Route("/dashboard", name="supplier_dashboard")
     */
    public function dashboardAction()
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        return $this->render('SupplierBundle:Dashboard:dashboard.html.twig', compact(
            'user',
            'supplier'
        ));
    }

}
