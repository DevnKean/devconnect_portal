<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contract;
use AppBundle\Entity\PotentialSupplier;
use AppBundle\Entity\Supplier;
use AppBundle\Entity\User;
use AppBundle\Form\PotentialSupplierType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class PotentialSupplierController
 *
 * @package AppBundle\Controller
 * @Route("potential-suppliers")
 */
class PotentialSupplierController extends Controller
{
    /**
     * @Route("", name="potential_supplier_all")
     */
    public function allAction()
    {
        $potentialSuppliers = $this->getDoctrine()->getRepository('AppBundle:PotentialSupplier')->findBy(['status' => PotentialSupplier::STATUS_POTENTIAL]);
        return $this->render('AppBundle:PotentialSupplier:all.html.twig', array(
            'potentialSuppliers' => $potentialSuppliers
        ));
    }

    /**
     * @Route("/actioned", name="potential_supplier_actioned")
     */
    public function actionedAction()
    {
        $potentialSuppliers = $this->getDoctrine()->getRepository('AppBundle:PotentialSupplier')->findBy(['status' => PotentialSupplier::STATUS_ACTIONED]);
        return $this->render('AppBundle:PotentialSupplier:actioned.html.twig', array(
            'potentialSuppliers' => $potentialSuppliers,
            'status' => PotentialSupplier::STATUS_ACTIONED
        ));
    }

    /**
     * @Route("/deleted", name="potential_supplier_deleted")
     */
    public function deletedAction()
    {
        $potentialSuppliers = $this->getDoctrine()->getRepository('AppBundle:PotentialSupplier')->findBy(['status' => PotentialSupplier::STATUS_DELETED]);
        return $this->render('AppBundle:PotentialSupplier:actioned.html.twig', array(
            'potentialSuppliers' => $potentialSuppliers,
            'status' => PotentialSupplier::STATUS_ACTIONED
        ));
    }

    /**
     * @Route("/{id}/edit", name="potential_supplier_edit")
     *
     * @param $request
     * @param $id
     * @param $passwordEncoder
     * @param $mailer
     *
     * @return Response
     */
    public function editAction(Request $request, $id, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer)
    {
        $manager = $this->getDoctrine()->getManager();
        $potentialSupplier = $manager->getRepository('AppBundle:PotentialSupplier')->find($id);

        if (!$potentialSupplier) {
            throw new NotFoundHttpException();
        }
        $form = $this->createForm(PotentialSupplierType::class, $potentialSupplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $potentialSupplier->setStatus(PotentialSupplier::STATUS_ACTIONED);
                $supplier = new Supplier();
                $supplier->setBusinessName($potentialSupplier->getBusinessName());
                $supplier->setAbnNumber($potentialSupplier->getAbnNumber());
                $supplier->setTradingName($potentialSupplier->getBusinessName());
                $supplier->setWebsite($potentialSupplier->getWebsite());
                $supplier->setAddress($potentialSupplier->getAddress());
                $user = new User();
                $user->setUsername($potentialSupplier->getUsername());
                $user->setPlainPassword($potentialSupplier->getInitialPassword());
                $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));
                $user->setRoles(['ROLE_ADMIN']);
                $user->setFirstName($potentialSupplier->getFirstName());
                $user->setLastName($potentialSupplier->getLastName());
                $user->setEmail($potentialSupplier->getEmail());
                $user->setJobTitle($potentialSupplier->getJobTitle());
                $user->setContactPhone($potentialSupplier->getContactNumber());
                $user->setSupplier($supplier);
                $potentialSupplier->setSupplier($supplier);
                $contract = new Contract();
                $contract->setStatus(Contract::STATUS_NEED_TO_PREPARE);
                $contract->setSupplier($supplier);
                $manager->persist($contract);
                $manager->persist($supplier);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('success', 'Supplier and user created');
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }
            return $this->redirectToRoute('potential_supplier_all');
        }

        return $this->render('AppBundle:PotentialSupplier:edit.html.twig', array(
            'form' => $form->createView(),
            'potentialSupplier' => $potentialSupplier
        ));
    }

    /**
     * @Route("/{id}/delete", name="potential_supplier_delete")
     * @param $id
     * @return Response
     */
    public function deleteAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $potentialSupplier = $manager->getRepository('AppBundle:PotentialSupplier')->find($id);

        if (!$potentialSupplier) {
            throw new NotFoundHttpException();
        }
        $potentialSupplier->setStatus(PotentialSupplier::STATUS_DELETED);
        $manager->flush();
        $this->addFlash('success', 'Potential Supplier Deleted');
        return $this->redirectToRoute('potential_supplier_all');
    }

}
