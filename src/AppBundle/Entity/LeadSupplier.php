<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * SupplierLead
 *
 * @ORM\Table(name="leads_suppliers")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LeadSupplierRepository")
 * @UniqueEntity(
 *     fields={"lead", "supplier"},
 *     message="This supplier is already allocated with this lead",
 *     errorPath="supplier"
 * )
 */
class LeadSupplier
{

    const RESULT_SUCCESS = 'Success';
    const RESULT_LOST = 'Lost';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Lead
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lead", inversedBy="leadSuppliers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lead;

    /**
     * @var Supplier
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="allocatedLeads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $supplier;

    /**
     * @ORM\Column(name="allocated_date", type="date")
     */
    private $allocatedDate;

    /**
     * @ORM\Column(name="result", type="string", nullable=true)
     */
    private $result;

    /**
     * @ORM\Column(name="lost_reason", type="string", nullable=true)
     */
    private $lostReason;

    /**
     * @var string
     * @ORM\Column(name="lost_reason_notes", type="text", nullable=true)
     */
    private $lostReasonNotes;

    /**
     * @ORM\Column(name="internal_notes", type="text", nullable=true)
     */
    private $internalNotes;

    /**
     * @ORM\Column(name="notes_to_outsourcer", type="text", nullable=true)
     */
    private $notesToOutsourcer;

    /**
     * @var string
     * @ORM\Column(name="lead_status", type="string", length=50)
     */
    private $leadStatus = 'Need to action';

    /**
     * @var \DateTime
     * @ORM\Column(name="commenced_at", type="date", nullable=true)
     *
     */
    private $commencedAt;

    /**
     * @var SupplierInvoice[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SupplierInvoice", mappedBy="leadSupplier")
     */
    private $supplierInvoices;

    /**
     * @var \DateTime
     * @ORM\Column(name="agreement_expired_at", type="date", nullable=true)
     */
    private $agreementExpiredAt;

    /**
     * LeadSupplier constructor.
     */
    public function __construct()
    {
        $this->leadStatus = 'Need to action';
    }

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
     * @return Lead
     */
    public function getLead()
    {
        return $this->lead;
    }

    /**
     * @param mixed $lead
     */
    public function setLead($lead)
    {
        $this->lead = $lead;
    }

    /**
     * @return Supplier
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * @param mixed $supplier
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
    }

    /**
     * @return mixed
     */
    public function getAllocatedDate()
    {
        return $this->allocatedDate;
    }

    /**
     * @param mixed $allocatedDate
     */
    public function setAllocatedDate($allocatedDate)
    {
        $this->allocatedDate = $allocatedDate;
    }

    /**
     * @return mixed
     */
    public function getInternalNotes()
    {
        return $this->internalNotes;
    }

    /**
     * @param mixed $internalNotes
     */
    public function setInternalNotes($internalNotes)
    {
        $this->internalNotes = $internalNotes;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getLostReason()
    {
        return $this->lostReason;
    }

    /**
     * @param mixed $lostReason
     */
    public function setLostReason($lostReason)
    {
        $this->lostReason = $lostReason;
    }

    /**
     * @return mixed
     */
    public function getNotesToOutsourcer()
    {
        return $this->notesToOutsourcer;
    }

    /**
     * @param mixed $notesToOutsourcer
     */
    public function setNotesToOutsourcer($notesToOutsourcer)
    {
        $this->notesToOutsourcer = $notesToOutsourcer;
    }

    public function isWon()
    {
        return $this->result === static::RESULT_SUCCESS;
    }

    public function isLost()
    {
        return $this->result === static::RESULT_LOST;
    }

    public function isPending()
    {
        return empty($this->result);
    }

    /**
     * @return string
     */
    public function getLostReasonNotes()
    {
        return $this->lostReasonNotes;
    }

    /**
     * @param string $lostReasonNotes
     */
    public function setLostReasonNotes($lostReasonNotes)
    {
        $this->lostReasonNotes = $lostReasonNotes;
    }

    public static function getAllocationResults()
    {
        return [
            self::RESULT_SUCCESS,
            self::RESULT_LOST
        ];
    }

    public static function getLostReasons()
    {
        return [
            'Won by other supplier',
            'Work did not proceed',
            'They sourced supplier outside of Connect',
        ];
    }

    /**
     * @return string
     */
    public function getLeadStatus()
    {
        return $this->leadStatus;
    }

    /**
     * @param string $leadStatus
     */
    public function setLeadStatus($leadStatus)
    {
        $this->leadStatus = $leadStatus;
    }

    /**
     * @return \DateTime
     */
    public function getCommencedAt()
    {
        return $this->commencedAt;
    }

    /**
     * @param \DateTime $commencedAt
     */
    public function setCommencedAt($commencedAt)
    {
        $this->commencedAt = $commencedAt;
    }

    /**
     * @return \DateTime
     */
    public function getAgreementExpiredAt()
    {
        return $this->agreementExpiredAt;
    }

    /**
     * @param \DateTime $agreementExpiredAt
     */
    public function setAgreementExpiredAt($agreementExpiredAt)
    {
        $this->agreementExpiredAt = $agreementExpiredAt;
    }

    public function getLastInvoiceReceivedDate()
    {
        $date = null;

        foreach ($this->getSupplierInvoices() as $supplierInvoice)
        {
            if (is_null($date) || $date <= $supplierInvoice->getReceivedAt())
            {
                $date = $supplierInvoice->getReceivedAt();
            }
        }

        return $date;
    }

    public function getNextInvoiceIssueDate()
    {
        $date = null;

        foreach ($this->getSupplierInvoices() as $supplierInvoice)
        {
            if ($supplierInvoice->getInvoice()) {
                if (is_null($date) || $date <= $supplierInvoice->getInvoice()->getNextInvoiceIssueAt())
                {
                    $date = $supplierInvoice->getInvoice()->getNextInvoiceIssueAt();
                }
            }

        }

        return $date;
    }

    public function hasOverDueSupplierInvoice()
    {
        if ($this->getNextInvoiceIssueDate() && $this->getNextInvoiceIssueDate() < new \DateTime()) {
            return true;
        }

        return false;
    }

    public function getLastPaymentDate()
    {
        $date = null;

        foreach ($this->getSupplierInvoices() as $supplierInvoice)
        {
            if ($supplierInvoice->getInvoice() && $supplierInvoice->getInvoice()->getPayments()->count()) {

                foreach ($supplierInvoice->getInvoice()->getPayments() as $payment) {
                    if ($payment->getStatus() == Payment::STATUS_SUCCESS) {
                        if (is_null($date) || $date >= $payment->getPaidAt())
                        {
                            $date = $payment->getPaidAt();
                        }
                    }
                }

            }
        }

        return $date;
    }

    public function getNextPaymentDueDate()
    {
        $date = null;

        foreach ($this->getSupplierInvoices() as $supplierInvoice)
        {
            if ($supplierInvoice->getInvoice()) {
                if (is_null($date) || $date <= $supplierInvoice->getInvoice()->getPaymentDueDate())
                {
                    $date = $supplierInvoice->getInvoice()->getPaymentDueDate();
                }
            }
        }

        return $date;
    }

    public function getTotalCommission()
    {
        $commission = 0;
        foreach ($this->getSupplierInvoices() as $supplierInvoice)
        {
            if ($supplierInvoice->getInvoice()) {
                $commission += $supplierInvoice->getInvoice()->getCommission();
            }
        }

        return $commission;
    }

    public function getTotalAmount()
    {
        $amount = 0;
        foreach ($this->getSupplierInvoices() as $supplierInvoice)
        {
            if ($supplierInvoice->getInvoice()) {
                $amount += $supplierInvoice->getTotal();
            }
        }

        return $amount;
    }

    /**
     * @return SupplierInvoice[]
     */
    public function getSupplierInvoices()
    {
        return $this->supplierInvoices;
    }

    /**
     * @param SupplierInvoice[] $supplierInvoices
     */
    public function setSupplierInvoices($supplierInvoices)
    {
        $this->supplierInvoices = $supplierInvoices;
    }

}

