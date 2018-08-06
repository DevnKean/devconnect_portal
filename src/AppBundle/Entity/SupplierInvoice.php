<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SupplierInvoice
 *
 * @ORM\Table(name="supplier_invoice")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SupplierInvoiceRepository")
 */
class SupplierInvoice
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var LeadSupplier
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\LeadSupplier", inversedBy="supplierInvoices")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id")
     */
    private $leadSupplier;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="issuedAt", type="date")
     */
    private $issuedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="receivedAt", type="date")
     */
    private $receivedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="paymentDueAt", type="date")
     */
    private $paymentDueAt;

    /**
     * @var int
     *
     * @ORM\Column(name="total", type="decimal", precision=10, scale=2)
     */
    private $total;

    /**
     * @var string
     *
     * @ORM\Column(name="referenceNumber", type="string", length=50)
     */
    private $referenceNumber;

    /**
     * @var mixed
     * @Assert\NotBlank(message="Please, upload the invoice as a PDF file.", groups={"create"})
     * @Assert\File(mimeTypes={ "application/pdf" })
     *
     * @ORM\Column(name="file", type="string", length=100)
     */
    private $file;

    /**
     * @var Invoice
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Invoice", mappedBy="supplierInvoice")
     */
    private $invoice;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set issuedAt
     *
     * @param \DateTime $issuedAt
     *
     * @return SupplierInvoice
     */
    public function setIssuedAt($issuedAt)
    {
        $this->issuedAt = $issuedAt;

        return $this;
    }

    /**
     * Get issuedAt
     *
     * @return \DateTime
     */
    public function getIssuedAt()
    {
        return $this->issuedAt;
    }

    /**
     * Set receivedAt
     *
     * @param \DateTime $receivedAt
     *
     * @return SupplierInvoice
     */
    public function setReceivedAt($receivedAt)
    {
        $this->receivedAt = $receivedAt;

        return $this;
    }

    /**
     * Get receivedAt
     *
     * @return \DateTime
     */
    public function getReceivedAt()
    {
        return $this->receivedAt;
    }

    /**
     * Set paymentDueAt
     *
     * @param \DateTime $paymentDueAt
     *
     * @return SupplierInvoice
     */
    public function setPaymentDueAt($paymentDueAt)
    {
        $this->paymentDueAt = $paymentDueAt;

        return $this;
    }

    /**
     * Get paymentDueAt
     *
     * @return \DateTime
     */
    public function getPaymentDueAt()
    {
        return $this->paymentDueAt;
    }

    /**
     * Set total
     *
     * @param integer $total
     *
     * @return SupplierInvoice
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set referenceNumber
     *
     * @param string $referenceNumber
     *
     * @return SupplierInvoice
     */
    public function setReferenceNumber($referenceNumber)
    {
        $this->referenceNumber = $referenceNumber;

        return $this;
    }

    /**
     * Get referenceNumber
     *
     * @return string
     */
    public function getReferenceNumber()
    {
        return $this->referenceNumber;
    }

    /**
     * Set file
     *
     * @param string $file
     *
     * @return SupplierInvoice
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return LeadSupplier
     */
    public function getLeadSupplier()
    {
        return $this->leadSupplier;
    }

    /**
     * @param LeadSupplier $leadSupplier
     */
    public function setLeadSupplier($leadSupplier)
    {
        $this->leadSupplier = $leadSupplier;
    }

    /**
     * @return Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param Invoice $invoice
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;
    }

    public function getCommissionTiers()
    {
       if ($this->getCommissionModel()) {
           return $this->getCommissionModel()->getCommissionTiers();
       }

       return null;
    }

    public function getCommissionModel()
    {
        /**@var Contract $contract */
        $contract = $this->getLeadSupplier()->getSupplier()->getContract();

        if (!$contract) {
            return null;
        }

        foreach ($contract->getContractServices() as $contractService) {
            if ($this->getLeadSupplier()->getLead()->getService() === $contractService->getService()) {
                return $contractService->getCommissionModel();
            }
        }

        return null;
    }

    public function findCommissionRate()
    {
        if (!$this->getCommissionModel()) {
            return null;
        }

        if ($this->getCommissionModel()->isFlatRate()) {
            return $this->getCommissionModel()->getFlatRate();
        }

        /**@var Contract $contract */
        $contract = $this->getLeadSupplier()->getSupplier()->getContract();

        $days = $this->getReceivedAt()->diff($contract->getStartDate())->d;

        foreach ($this->getCommissionTiers() as $commissionModel) {
            if ($this->getTotal() >= $commissionModel->getLowerThreshold()) {
                if (($commissionModel->getUpperThreshold() && $this->getTotal() <= $commissionModel->getUpperThreshold())
                    || $commissionModel->getUpperThreshold() == null) {

                    if ($days <= 365) {
                        return $commissionModel->getRateYearOne();
                    }

                    if ($days <= (365 * 2)) {
                        return $commissionModel->getRateYearTwo();
                    }

                    return $commissionModel->getRateYearThree();
                }
            }
        }

        return null;
    }

    public function findCommissionTier()
    {
        if (!$this->getCommissionTiers()) {
            return null;
        }

        foreach ($this->getCommissionTiers() as $commissionTier) {
            if ($this->getTotal() >= $commissionTier->getLowerThreshold()) {
                if (($commissionTier->getUpperThreshold() && $this->getTotal() <= $commissionTier->getUpperThreshold())
                    || $commissionTier->getUpperThreshold() == null) {

                    return $commissionTier;
                }
            }
        }

        return null;
    }

    public function findTierLevel()
    {
        if ($this->findCommissionTier()) {
            return $this->findCommissionTier()->getTierLevel();
        }

        return null;
    }

    public function getPaymentDueDate()
    {
        /** @var Contract $contract */
        $contract = $this->getLeadSupplier()->getSupplier()->getContracts()->first();
        $interval = $contract->getPaymentTerm();
        $dueDate = clone $this->paymentDueAt;
        return $dueDate->add(new \DateInterval("P{$interval}D"));
    }
}

