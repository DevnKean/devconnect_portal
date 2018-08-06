<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contract;
use AppBundle\Entity\Profile;
use AppBundle\Entity\SupplierProfile;
use AppBundle\Form\ContractType;
use AppBundle\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ContractController
 *
 * @Route("/contract")
 * @package AppBundle\Controller
 *
 */
class ContractController extends Controller
{
    /**
     * @Route("/create", name="contract_create")
     *
     * @param Request $request
     * @param FileUploader $fileUploader
     *
     * @return Response
     */
    public function createAction(Request $request, FileUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $contract = new Contract();
        $form = $this->createForm(ContractType::class, $contract);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $contract->getFile();
            $fileName = $fileUploader->upload($file);

            $contract->setFile($fileName);

            $manager = $this->getDoctrine()->getManager();

            if ($contract->getStatus() == Contract::STATUS_APPROVED) {
                foreach ($contract->getContractServices() as $contractService) {
                    $profiles = $contractService->getService()->getProfiles();
                    $supplier = $contract->getSupplier();
                    foreach ($profiles as $profile) {
                        if (!$supplier->getProfiles()->contains($profile)) {
                            $supplierProfile = new SupplierProfile();
                            $supplierProfile->setProfile($profile);
                            $supplierProfile->setSupplier($contract->getSupplier());
                            $supplierProfile->setStatus($profile->getInitialStatus());
                            $supplierProfile->setIsDisabled(false);
                            $manager->persist($supplierProfile);
                        }

                    }
                }
            }

            $manager->persist($contract);
            $manager->flush();
            $this->addFlash('success', 'Contract Created');
            return $this->redirectToRoute('contract_all');
        }

        return $this->render('AppBundle:Contract:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/{id}/edit", name="contract_edit")
     *
     * @param $request
     * @param $id
     * @param $fileUploader
     *
     * @return Response
     */
    public function editAction(Request $request, $id, FileUploader $fileUploader)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $manager = $this->getDoctrine()->getManager();
        /** @var Contract $contract */
        $contract = $manager->find('AppBundle:Contract', $id);
        $filePath = $contract->getFile();
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($file = $contract->getFile()) {
                $fileName = $fileUploader->upload($file);
                $contract->setFile($fileName);
            } else {
                $contract->setFile($filePath);
            }

            if ($contract->getStatus() == Contract::STATUS_APPROVED) {
                foreach ($contract->getContractServices() as $contractService) {
                    $profiles = $contractService->getService()->getProfiles();
                    $supplier = $contract->getSupplier();
                    foreach ($profiles as $profile) {
                        if (!$supplier->getProfiles()->contains($profile)) {
                            $supplierProfile = new SupplierProfile();
                            $supplierProfile->setProfile($profile);
                            $supplierProfile->setSupplier($contract->getSupplier());
                            $supplierProfile->setStatus($profile->getInitialStatus());
                            $supplierProfile->setIsDisabled(false);
                            $manager->persist($supplierProfile);
                        }
                    }
                }
            }

            $manager->persist($contract);
            $manager->flush();
            $this->addFlash('success', 'Contract Updated');
            return $this->redirectToRoute('contract_all');
        }

        return $this->render('AppBundle:Contract:create.html.twig', array(
           'form' => $form->createView()
        ));
    }

    /**
     * @Route("", name="contract_all")
     */
    public function allAction()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $em = $this->getDoctrine()->getManager();
        $contracts = $em->getRepository('AppBundle:Contract')->findAll();
        return $this->render('AppBundle:Contract:all.html.twig', compact('contracts'));
    }

    /**
     * @Route("/{id}/delete", name="contract_delete")
     *
     * @param $id
     *
     * @return Response
     */
    public function deleteAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $manager = $this->getDoctrine()->getManager();

        /** @var Contract $contract */
        $contract = $manager->find('AppBundle:Contract', $id);

        if (!$contract) {
            throw new NotFoundHttpException();
        }

        $supplier = $contract->getSupplier();
        if (!$supplier->getContracts()->count()) {
            foreach ($contract->getContractServices() as $contractService) {
                $profiles = $contractService->getService()->getProfiles();
                foreach ($profiles as $profile) {
                    foreach ($supplier->getSupplierProfiles() as $supplierProfile) {
                        if ($supplierProfile->getProfile() === $profile) {
                            $manager->remove($supplierProfile);
                        }
                    }
                }
            }
        }

        $manager->remove($contract);
        $manager->flush();
        $this->addFlash('success', 'Contract Deleted Successfully');
        return $this->redirectToRoute('contract_all');
    }
}
