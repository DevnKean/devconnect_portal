<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contract
 *
 * @ORM\Table(name="contract")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContractRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Contract
{
    use TimestampableEntity;

    const STATUS_NEED_TO_PREPARE = 'Need to prepare';
    const STATUS_SENT = 'Sent';
    const STATUS_PENDING = 'Pending';
    const STATUS_APPROVED = 'Approved';
    const STATUS_EXPIRED= 'Expired';

    const NOT_APPLICABLE = 'Not Applicable';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="date", nullable=true)
     */
    private $endDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sent_at", type="date", nullable=true)
     */
    private $sentAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="received_at", type="date", nullable=true)
     */
    private $receivedAt;

    /**
     * @var Supplier
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="contracts")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    private $supplier;

    /**
     * @ORM\Column(name="file", type="string", nullable=true)
     * @Assert\NotBlank(message="Please, upload the contract as a PDF file.", groups={"create"})
     * @Assert\File(
     *     mimeTypes={ "application/pdf" },
     *     mimeTypesMessage="Only PDF files accepted"
     * )
     */
    private $file;

    /**
     * @var ContractService[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ContractService", mappedBy="contract", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $contractServices;

    /**
     * @var int
     * @ORM\Column(name="payment_term", type="integer", nullable=true)
     */
    private $paymentTerm;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    public function __construct()
    {
        $this->contractServices = new ArrayCollection();
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
     * Set status
     *
     * @param string $status
     *
     * @return Contract
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Contract
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Contract
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set sentAt
     *
     * @param \DateTime $sentAt
     *
     * @return Contract
     */
    public function setSentAt($sentAt)
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    /**
     * Get sentAt
     *
     * @return \DateTime
     */
    public function getSentAt()
    {
        return $this->sentAt;
    }

    /**
     * Set receivedAt
     *
     * @param \DateTime $receivedAt
     *
     * @return Contract
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
     * @return Supplier
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * @param Supplier $supplier
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param mixed $deletedAt
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * @return ContractService[] | ArrayCollection
     */
    public function getContractServices()
    {
        return $this->contractServices;
    }

    /**
     * @param ContractService $contractServices
     */
    public function setContractServices($contractServices)
    {
        $this->contractServices = $contractServices;
    }

    /**
     * @return int
     */
    public function getPaymentTerm()
    {
        return $this->paymentTerm;
    }

    /**
     * @param int $paymentTerm
     */
    public function setPaymentTerm($paymentTerm)
    {
        $this->paymentTerm = $paymentTerm;
    }

    /**
     * @param ContractService $contractService
     */
    public function removeContractService(ContractService $contractService) {
        if (!$this->contractServices->contains($contractService)) {
            return;
        }

        $this->contractServices->removeElement($contractService);
        $contractService->setContract(null);
    }

    public function addContractService(ContractService $contractService)
    {
        if ($this->contractServices->contains($contractService)) {
            return;
        }

        $this->contractServices[] = $contractService;
        $contractService->setContract($this);
    }

    public function getDaysLeft()
    {
        if ($this->endDate <= new \DateTime()) {
            return 0;
        }

        return $this->endDate->diff(new \DateTime())->days;
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_NEED_TO_PREPARE,
            self::STATUS_SENT,
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
            self::STATUS_EXPIRED,
        ];
    }

}

