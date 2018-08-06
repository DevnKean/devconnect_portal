<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contract;
use AppBundle\Entity\Lead;
use AppBundle\Entity\LeadSupplier;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\PotentialSupplier;
use AppBundle\Entity\Supplier;
use AppBundle\Entity\SupplierProfile;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    /**
     * @Route("/dashboard", name="admin_dashboard")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $manager = $this->getDoctrine()->getManager();

        if ($request->isMethod('POST')) {
            $startDate = \DateTime::createFromFormat('Y-m-d', $request->get('startDate'));
            $endDate = \DateTime::createFromFormat('Y-m-d', $request->get('endDate'));
            $leads = $manager->getRepository('AppBundle:Lead')->getLeadsByDates($startDate, $endDate);
        } else {
            $leads = $manager->getRepository('AppBundle:Lead')->findAll();
        }

        $campaign = array_filter($leads, function (Lead $lead) {
            return $lead->isWon();
        });

        $pendingLeads = $manager->getRepository('AppBundle:Lead')->findBy(['status' => Lead::STATUS_PENDING]);

        $revenue = 0;
        array_map(function(Lead $lead) use(&$revenue) {
            if ($lead->getCampaign()) {
                $revenue += $lead->getTotalAmount();
            }

        }, $leads);

        $commission = 0;
        array_map(function(Lead $lead) use (&$commission) {
            if ($lead->getCampaign()) {
                $commission += $lead->getTotalCommission();
            }
        }, $leads);

        $supplierProfiles = $manager->getRepository('AppBundle:SupplierProfile')->findBy(['status' => SupplierProfile::STATUS_PENDING]);
        $leadConversion = count($leads) ? count($campaign) / count($leads) : 0;

        $overDuePayments = $manager->getRepository('AppBundle:Invoice')->getOverDuePayments();
        $overDueInvoices = $manager->getRepository('AppBundle:LeadSupplier')->findAll();
        $overDueInvoices = array_filter($overDueInvoices, function (LeadSupplier $leadSupplier) {
            return $leadSupplier->isWon() && $leadSupplier->hasOverDueSupplierInvoice();
        });

        $potentialSupplier = $manager->getRepository('AppBundle:PotentialSupplier')->findBy(['status' => PotentialSupplier::STATUS_POTENTIAL]);

        $suppliers = $manager->getRepository('AppBundle:Supplier')->findAll();

        $approvedCount = 0;
        $pendingCount = 0;
        $feedbackCount = 0;
        $notStartedCount = 0;

        foreach ($suppliers as $supplier) {
            if ($supplier->isApproved()) {
                list ($incomplete, $pending, $feedback, $approved) = array_values($supplier->getProfileStatusCount());
                $approvedCount += $approved;
                $pendingCount += $pending;
                $feedbackCount += $feedback;
                $notStartedCount += $incomplete;
            }
        }

        $approvedSupplier = 0;
        $pendingSupplier = 0;
        $notStartedSupplier = 0;
        foreach ($suppliers as $supplier) {
            if ($supplier->isApproved()) {
                if ($supplier->getProfileOverallStatus() == SupplierProfile::STATUS_APPROVED) {
                    $approvedSupplier++;
                }
                if ($supplier->getProfileOverallStatus() == SupplierProfile::STATUS_PENDING) {
                    $pendingSupplier++;
                }
                if ($supplier->getProfileOverallStatus() == SupplierProfile::STATUS_INCOMPLETE) {
                    $notStartedSupplier++;
                }
            }
        }

        foreach ($suppliers as $supplier) {
            if ($supplier->isApproved()) {
                if ($supplier->getProfileOverallStatus() == SupplierProfile::STATUS_APPROVED) {
                    $approvedSupplier++;
                }
                if ($supplier->getProfileOverallStatus() == SupplierProfile::STATUS_PENDING) {
                    $pendingSupplier++;
                }
                if ($supplier->getProfileOverallStatus() == SupplierProfile::STATUS_INCOMPLETE) {
                    $notStartedSupplier++;
                }
            }
        }

        try {
            $contractSentCount = $manager->getRepository('AppBundle:Supplier')->getContractSentSuppliersCount(Contract::STATUS_SENT);
        } catch (\Exception $e) {
            $contractSentCount = 0;
        }

        try {
            $contractNeedToPrepareCount = $manager->getRepository('AppBundle:Supplier')->getContractSentSuppliersCount(Contract::STATUS_NEED_TO_PREPARE);
        } catch (\Exception $e) {
            $contractNeedToPrepareCount = 0;
        }

        try {
            $contractApprovedCount = $manager->getRepository('AppBundle:Supplier')->getContractSentSuppliersCount(Contract::STATUS_APPROVED);
        } catch (\Exception $e) {
            $contractApprovedCount = 0;
        }
        return $this->render('AppBundle:Dashboard:dashboard.html.twig', compact(
            'leads',
            'pendingLeads',
            'leadConversion',
            'campaign',
            'revenue',
            'commission',
            'supplierProfiles',
            'startDate',
            'endDate',
            'overDuePayments',
            'overDueInvoices',
            'potentialSupplier',
            'approvedCount',
            'pendingCount',
            'notStartedCount',
            'approvedSupplier',
            'pendingSupplier',
            'notStartedSupplier',
            'feedbackCount',
            'contractSentCount',
            'contractNeedToPrepareCount',
            'contractApprovedCount'
        ));
    }

}
