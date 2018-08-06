<?php
/**
 * Created by PhpStorm.
 * User: Lixing
 * Date: 11/9/17
 * Time: 10:28 PM
 */

namespace AppBundle\Controller;

use AppBundle\Service\LeadFactory;
use AppBundle\Service\LeadProcessor;
use AppBundle\Service\SupplierFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GravityController
 *
 * @package AppBundle\Controller
 * @Route("/gravity")
 */
class GravityController extends Controller {

    /**
     * @Route("/lead", name="gravity_lead")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function leadAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $manager = $this->getDoctrine()->getManager();
            $leadFactory = new LeadFactory($manager, $request);
            $leadFactory->process();
            return new JsonResponse(['ok']);
        }

        return new JsonResponse([]);
    }

    /**
     * @Route("/supplier", name="gravity_supplier")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function supplierAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $manager = $this->getDoctrine()->getManager();
            $supplierFactory = new SupplierFactory($manager, $request);
            $supplierFactory->process();
            return new JsonResponse(['ok']);
        }

        return new JsonResponse([]);
    }
}