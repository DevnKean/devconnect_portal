<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CommissionModel;
use AppBundle\Entity\CommissionTier;
use AppBundle\Form\CommissionModelData;
use AppBundle\Form\CommissionTierType;
use AppBundle\Form\CommissionModelType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Port\Csv\CsvReader;
use Port\Doctrine\DoctrineWriter;
use Port\Excel\ExcelReader;
use Port\Steps\StepAggregator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class CommissionController
 *
 * @package AppBundle\Controller
 * @Route("/commission-models")
 */
class CommissionController extends Controller
{
    /**
     * @Route("", name="commission_all")
     */
    public function listAction()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $manager = $this->getDoctrine()->getManager();

        $models = $manager->getRepository('AppBundle:CommissionModel')->findAll();
        return $this->render('AppBundle:Commission:list.html.twig', compact('models'));
    }

    /**
     * @Route("/{id}/tiers", name="commission_models")
     *
     * @param $id
     *
     * @return Response
     */
    public function modelsAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();
        $model = $manager->getRepository('AppBundle:CommissionModel')->find($id);
        if ($model->isFlatRate()) {
            throw new AccessDeniedHttpException();
        }
        $tiers = $model->getCommissionTiers();

        return $this->render('AppBundle:Commission:models.html.twig', compact('tiers', 'model'));
    }

    /**
     * @Route("/{id}/tier/new", name="commission_model_new")
     *
     * @param $request
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tierNewAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();
        $model = $manager->getRepository('AppBundle:CommissionModel')->find($id);

        $tier = new CommissionTier();

        $model->addCommissionTier($tier);

        $form = $this->createForm(CommissionTierType::class, $tier);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($model);
            $manager->flush();
            $this->addFlash('success', 'Model Added');
            return $this->redirectToRoute('commission_models', ['id' => $id]);
        }

        return $this->render('AppBundle:Commission:tier_new.html.twig', array(
            'form' => $form->createView(),
            'model' => $model
        ));
    }

    /**
     * @Route("/{id}/tier/{tierId}/edit", name="commission_model_edit")
     *
     * @param $request
     * @param $id
     * @param $tierId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tierEditAction(Request $request, $id, $tierId)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();
        $model = $manager->getRepository('AppBundle:CommissionModel')->find($id);

        $tier = $manager->getRepository('AppBundle:CommissionTier')->findOneBy(['commissionModel' => $model, 'id' => $tierId]);

        $form = $this->createForm(CommissionTierType::class, $tier);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($tier);
            $manager->flush();
            $this->addFlash('success', 'Model Update');
            return $this->redirectToRoute('commission_models', ['id' => $id]);
        }

        return $this->render('AppBundle:Commission:tier_edit.html.twig', array(
            'form' => $form->createView(),
            'model' => $model
        ));
    }

    /**
     * @Route("/{id}/tier/{tierId}/delete", name="commission_model_delete")
     *
     * @param $id
     * @param $tierId
     *
     * @return Response
     */
    public function tierDeleteAction($id, $tierId)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();
        $model = $manager->getRepository('AppBundle:CommissionModel')->find($id);

        $tier = $manager->getRepository('AppBundle:CommissionTier')->findOneBy(['commissionModel' => $model, 'id' => $tierId]);

        $manager->remove($tier);
        $manager->flush();
        $this->addFlash('success', 'Model Removed');
        return $this->redirectToRoute('commission_models', ['id' => $id]);
    }

    /**
     * @Route("/{id}/delete", name="commission_delete")
     *
     * @param $id
     *
     * @return Response
     */
    public function modelDeleteAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();
        $model = $manager->getRepository('AppBundle:CommissionModel')->find($id);
        try {
            $manager->remove($model);
            $manager->flush();
            $this->addFlash('success', 'Commission Model deleted');
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->addFlash('danger', 'This model cannot be deleted, because it is in use now.');
        }

        return $this->redirectToRoute('commission_all');
    }

    /**
     * @Route("/{id}/edit", name="commission_edit")
     *
     * @param $id
     * @param $request
     *
     * @return Response
     */
    public function modelEditAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();
        $model = $manager->getRepository('AppBundle:CommissionModel')->find($id);
        $modelData = new CommissionModelData($model);
        $form = $this->createForm(CommissionModelType::class, $modelData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($modelData->isFlatRate()) {
                foreach ($model->getCommissionTiers() as $commissionTier) {
                    $manager->remove($commissionTier);
                }
                $model->setFlatRate($modelData->getFlatRate());
            } else {
                $model->setFlatRate(null);
            }
            $model->setName($modelData->getName());
            $manager->persist($model);
            $manager->flush();
            $this->addFlash('success', 'Commission Added');
            return $this->redirectToRoute('commission_all');
        }

        return $this->render('AppBundle:Commission:commission_new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/new", name="commission_new")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function commissionNewAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();
        $model = new CommissionModel();
        $commissionModelData = new CommissionModelData($model);
        $form = $this->createForm(CommissionModelType::class, $commissionModelData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            try {
                if (!$commissionModelData->isFlatRate() && $file = $commissionModelData->getFile()) {
                    if ($file->guessExtension() == 'xls') {
                        $file = new \SplFileObject($file->getRealPath());
                        $reader = new ExcelReader($file, 0);
                        $reader->next();
                    } else {
                        $file = new \SplFileObject($file->getRealPath());
                        $reader = new CsvReader($file);
                        $reader->setHeaderRowNumber(0);
                    }

                    // Tell the reader that the first row in the CSV file contains column headers
                    foreach ($reader as $row) {
                        $tier = new CommissionTier();
                        $tier->setTierLevel($row['tierLevel']);
                        $tier->setLowerThreshold($row['lowerThreshold']);
                        $tier->setUpperThreshold($row['upperThreshold']);
                        $tier->setRateYearOne($row['rateYearOne']);
                        $tier->setRateYearTwo($row['rateYearTwo']);
                        $tier->setRateYearThree($row['rateYearThree']);
                        $model->addCommissionTier($tier);
                    }
                } else {
                    $model->setFlatRate($commissionModelData->getFlatRate());
                }
                $model->setName($commissionModelData->getName());
                $manager->persist($model);
                $manager->flush();
                $this->addFlash('success', 'Commission Added');
                return $this->redirectToRoute('commission_all');
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }

        }

        return $this->render('AppBundle:Commission:commission_new.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
