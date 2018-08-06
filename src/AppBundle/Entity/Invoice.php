<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Invoice
 *
 * @ORM\Table(name="invoice")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InvoiceRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Invoice
{
    use TimestampableEntity;

    const STATUS_PENDING = 'Awaiting Payment';
    const STATUS_PAID = 'Paid';
    const STATUS_VOID = 'Void';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var SupplierInvoice
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\SupplierInvoice", inversedBy="invoice")
     * @ORM\JoinColumn(name="supplier_invoice_id", referencedColumnName="id")
     */
    private $supplierInvoice;

    /**
     * @var float
     *
     * @ORM\Column(name="commission_rate", type="decimal", precision=10, scale=4)
     */
    private $commissionRate;

    /**
     * @var float
     *
     * @ORM\Column(name="commission", type="decimal", precision=10, scale=2)
     */
    private $commission;

    /**
     * @var string
     * @ORM\Column(name="tier_level", type="string", length=10)
     */
    private $tierLevel;

    /**
     * @var string
     *
     * @ORM\Column(name="xero_id", type="string", length=255)
     */
    private $xeroId;

    /**
     * @var \DateTime
     * @ORM\Column(name="due_at", type="date")
     */
    private $dueAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="next_invoice_issue_at", type="date")
     */
    private $nextInvoiceIssueAt;

    /**
     * @var Payment[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Payment", mappedBy="invoices")
     */
    private $payments;

    /**
     * @var \DateTime
     * @ORM\Column(name="sent_to_supplier_at", type="datetime")
     */
    private $sentToSupplierAt;

    /**
     * @var string
     * @ORM\Column(name="status", type="string", length=20)
     */
    private $status;

    public function __construct()
    {
        $this->status = self::STATUS_PENDING;
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
     * Set xeroId
     *
     * @param string $xeroId
     *
     * @return Invoice
     */
    public function setXeroId($xeroId)
    {
        $this->xeroId = $xeroId;

        return $this;
    }

    /**
     * Get xeroId
     *
     * @return string
     */
    public function getXeroId()
    {
        return $this->xeroId;
    }

    /**
     * @return SupplierInvoice
     */
    public function getSupplierInvoice()
    {
        return $this->supplierInvoice;
    }

    /**
     * @param SupplierInvoice $supplierInvoice
     */
    public function setSupplierInvoice(SupplierInvoice $supplierInvoice)
    {
        $this->supplierInvoice = $supplierInvoice;
    }

    /**
     * @return float
     */
    public function getCommissionRate()
    {
        return $this->commissionRate;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setCommissionRate()
    {
        $this->commissionRate = $this->findCommissionRate();
    }

    /**
     * @return float
     */
    public function getCommission()
    {
        return $this->commission;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setCommission()
    {
        $this->commission = $this->commissionRate * $this->supplierInvoice->getTotal();
    }

    /**
     * @return string
     */
    public function getTierLevel()
    {
        return $this->tierLevel;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setTierLevel()
    {
        $this->tierLevel = $this->findTierLevel();
    }

    /**
     * @return \DateTime
     */
    public function getNextInvoiceIssueAt()
    {
        return $this->nextInvoiceIssueAt;
    }

    /**
     * @param \DateTime $nextInvoiceIssueAt
     */
    public function setNextInvoiceIssueAt($nextInvoiceIssueAt)
    {
        $this->nextInvoiceIssueAt = $nextInvoiceIssueAt;
    }

    public function getPaymentDueDate()
    {
       return $this->supplierInvoice->getPaymentDueDate();
    }

    public function getCommissionModels()
    {
        return $this->supplierInvoice->getCommissionTiers();
    }

    public function findCommissionRate()
    {
        return $this->supplierInvoice->findCommissionRate();
    }

    public function findTierLevel()
    {
        return $this->supplierInvoice->findTierLevel();
    }

    /**
     * @return Payment[] | ArrayCollection
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * @param Payment[] $payments
     */
    public function setPayments($payments)
    {
        $this->payments = $payments;
    }

   public function getSupplier()
   {
       return $this->supplierInvoice->getLeadSupplier()->getSupplier();
   }

    public function getLead()
    {
        return $this->supplierInvoice->getLeadSupplier()->getLead();
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return \DateTime
     */
    public function getDueAt()
    {
        return $this->dueAt;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setDueAt()
    {
        $this->dueAt = $this->supplierInvoice->getPaymentDueDate();
    }

    /**
     * @return \DateTime
     */
    public function getSentToSupplierAt()
    {
        return $this->sentToSupplierAt;
    }

    /**
     * @param \DateTime $sentToSupplierAt
     */
    public function setSentToSupplierAt($sentToSupplierAt)
    {
        $this->sentToSupplierAt = $sentToSupplierAt;
    }

}

