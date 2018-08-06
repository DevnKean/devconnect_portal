<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Lead
 *
 * @ORM\Table(name="lead")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LeadRepository")
 */
class Lead
{
    const STATUS_APPROVE = 'Approved';
    const STATUS_PENDING = 'Pending';
    const STATUS_CONTACT_REQUIRED = 'Contact Required';
    const STATUS_UNABLE_TO_CONTACT = 'Unable to contact';
    const STATUS_EXPIRED = 'Expired';
    const STATUS_DECLINED = 'Declined';

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
     * @ORM\Column(name="status", type="string", length=20, nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="lostReason", type="text", length=255, nullable=true)
     */
    private $lostReason;

    /**
     * @var Form
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Form", inversedBy="leads")
     * @ORM\JoinColumn(name="form_id", referencedColumnName="id")
     */
    private $form;

    /**
     * @var string
     *
     * @ORM\Column(name="rawData", type="text")
     */
    private $rawData;

    /**
     * @var ArrayCollection | LeadSupplier[]
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\LeadSupplier",
     *     mappedBy="lead",
     *     fetch="EXTRA_LAZY",
     *     orphanRemoval=true,
     *     cascade={"persist", "remove"}
     * )
     * @Assert\Valid()
     *
     */
    private $leadSuppliers;

    /**
     * @var Service
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Service", inversedBy="leads")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     */
    private $service;

    /**
     * @var string
     * @ORM\Column(name="function", type="json_array", nullable=true)
     */
    private $function;

    /**
     * @var Client
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Client", inversedBy="leads")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @var string
     * @ORM\Column(name="business_name", length=20, nullable=true)
     */
    private $businessName;

    /**
     * @var string
     * @ORM\Column(name="contact_name", length=20, nullable=true)
     */
    private $contactName;

    /**
     * @var string
     * @ORM\Column(name="contact_email", length=50, nullable=true)
     */
    private $contactEmail;

    /**
     * @var string
     * @ORM\Column(name="contact_phone", length=20, nullable=true)
     */
    private $contactPhone;

    /**
     * @var string
     * @ORM\Column(name="campaign_length", length=20)
     */
    private $campaignLength;

    /**
     * @var string
     * @ORM\Column(name="estimate_type", length=50)
     */
    private $estimateType;

    /**
     * @var string
     * @ORM\Column(name="type", length=50, nullable=true)
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(name="estimate_type_option", length=50)
     */
    private $estimateTypeOption;

    /**
     * @var \DateTime;
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var int
     * @ORM\Column(name="entry_id", type="integer")
     */
    private $entryId;

    /**
     * @var LeadNote[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\LeadNote", mappedBy="lead")
     */
    private $leadNotes;

    /**
     * @var string
     * @ORM\Column(name="unique_id", type="string")
     */
    private $uniqueID;

    /**
     * @var LeadTracker[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\LeadTracker", mappedBy="lead")
     * @ORM\OrderBy({"createdAt" = "ASC"})
     */
    private $leadTrackers;

    /**
     * @var LeadStatusLog[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\LeadStatusLog", mappedBy="lead")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $leadStatusLogs;

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
     * @return Lead
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
     * Set lostReason
     *
     * @param string $lostReason
     *
     * @return Lead
     */
    public function setLostReason($lostReason)
    {
        $this->lostReason = $lostReason;

        return $this;
    }

    /**
     * Get lostReason
     *
     * @return string
     */
    public function getLostReason()
    {
        return $this->lostReason;
    }

    /**
     * Set rawData
     *
     * @param string $rawData
     *
     * @return Lead
     */
    public function setRawData($rawData)
    {
        $this->rawData = $rawData;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get rawData
     *
     * @return string
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param mixed $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * @return leadSupplier[] | ArrayCollection
     */
    public function getLeadSuppliers()
    {
        return $this->leadSuppliers;
    }

    /**
     * @param mixed $leadSuppliers
     */
    public function setLeadSuppliers($leadSuppliers)
    {
        $this->leadSuppliers = $leadSuppliers;
    }

    /**
     * @param LeadSupplier $leadSupplier
     */
    public function removeLeadSupplier(LeadSupplier $leadSupplier) {
        if (!$this->leadSuppliers->contains($leadSupplier)) {
            return;
        }

        $this->leadSuppliers->removeElement($leadSupplier);
        $leadSupplier->setLead(null);
    }

    public function addLeadSupplier(LeadSupplier $leadSupplier)
    {
        if ($this->leadSuppliers->contains($leadSupplier)) {
            return;
        }

        $this->leadSuppliers[] = $leadSupplier;
        $leadSupplier->setLead($this);
    }

    /**
     * @return mixed|null
     */
    public function getWinningSupplier()
    {
        foreach ($this->leadSuppliers as $leadSupplier) {
            if ($leadSupplier->isWon()) {
                return $leadSupplier->getSupplier();
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getBusinessName()
    {
        return $this->businessName;
    }

    /**
     * @param string $businessName
     *
     * @return Lead
     */
    public function setBusinessName($businessName)
    {
        $this->businessName = $businessName;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * @param string $contactName
     *
     * @return Lead
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * @param string $contactEmail
     *
     * @return Lead
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    /**
     * @param string $contactPhone
     *
     * @return Lead
     */
    public function setContactPhone($contactPhone)
    {
        $this->contactPhone = $contactPhone;

        return $this;
    }

    public function isWon()
    {
        $isWon = false;
        $this->leadSuppliers->map(function (LeadSupplier $leadSupplier) use(&$isWon) {
           if ($leadSupplier->isWon()) {
               $isWon = true;
           }
        });

        return $isWon;
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_APPROVE,
            self::STATUS_PENDING,
            self::STATUS_CONTACT_REQUIRED,
            self::STATUS_UNABLE_TO_CONTACT,
            self::STATUS_EXPIRED,
            self::STATUS_DECLINED
        ];
    }

    /**
     * @return int
     */
    public function getEntryId()
    {
        return $this->entryId;
    }

    /**
     * @param int $entryId
     */
    public function setEntryId($entryId)
    {
        $this->entryId = $entryId;
    }

    public function __toString()
    {
        return $this->uniqueID;
    }

    /**
     * @return null | LeadSupplier
     */
    public function getCampaign()
    {
        $campaign = null;
        $this->leadSuppliers->map(function (LeadSupplier $leadSupplier) use(&$campaign) {
            if ($leadSupplier->isWon()) {
               $campaign = $leadSupplier;
            }
        });

        return $campaign;
    }

    public function getTotalCommission()
    {
       return $this->getCampaign()->getTotalCommission();
    }

    public function getTotalAmount()
    {
       return $this->getCampaign()->getTotalAmount();
    }

    public function getLastInvoiceReceivedDate()
    {
       return $this->getCampaign()->getLastInvoiceReceivedDate();
    }

    public function getNextPaymentDue()
    {
        return $this->getCampaign()->getNextPaymentDueDate();
    }

    public function getNextInvoiceIssueDate()
    {
        return $this->getCampaign()->getNextInvoiceIssueDate();
    }

    /**
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param Service $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return string
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * @param string $function
     */
    public function setFunction($function)
    {
        $this->function = $function;
    }

    /**
     * @return LeadNote[]
     */
    public function getLeadNotes()
    {
        return $this->leadNotes;
    }

    /**
     * @param LeadNote[] $leadNotes
     */
    public function setLeadNotes($leadNotes)
    {
        $this->leadNotes = $leadNotes;
    }

    /**
     * @return string
     */
    public function getCampaignLength()
    {
        return $this->campaignLength;
    }

    /**
     * @param string $campaignLength
     */
    public function setCampaignLength($campaignLength)
    {
        $this->campaignLength = $campaignLength;
    }

    /**
     * @return string
     */
    public function getEstimateType()
    {
        return $this->estimateType;
    }

    /**
     * @param string $estimateType
     */
    public function setEstimateType($estimateType)
    {
        $this->estimateType = $estimateType;
    }

    /**
     * @return string
     */
    public function getEstimateTypeOption()
    {
        return $this->estimateTypeOption;
    }

    /**
     * @param string $estimateTypeOption
     */
    public function setEstimateTypeOption($estimateTypeOption)
    {
        $this->estimateTypeOption = $estimateTypeOption;
    }

    public function getTimeFrame()
    {
        return $this->createdAt->diff($this->updatedAt)->format('%a');
    }

    /**
     * @return string
     */
    public function getUniqueID()
    {
        return $this->uniqueID;
    }

    /**
     * @param string $uniqueID
     */
    public function setUniqueID($uniqueID)
    {
        $this->uniqueID = $uniqueID;
    }

    /**
     * @return LeadTracker[] | ArrayCollection
     */
    public function getLeadTrackers()
    {
        return $this->leadTrackers;
    }

    /**
     * @param LeadTracker[] $leadTrackers
     */
    public function setLeadTrackers($leadTrackers)
    {
        $this->leadTrackers = $leadTrackers;
    }

    public function getTrackerReport()
    {
        $report = [];

        $date = null;
        foreach ($this->getLeadTrackers() as $tracker) {
            $data = [
                'lead' => $tracker->getLead(),
                'status' => $tracker->getStatus(),
                'date' => $tracker->getCreatedAt(),
            ];
            if (!$date) {
                $date = $tracker->getCreatedAt();
                $data['duration'] = 0;
            } else {
                $data['duration'] = $tracker->getCreatedAt()->diff($date)->days;
            }

            $report[] = $data;
        }

        return $report;
    }

    /**
     * @return LeadStatusLog[]
     */
    public function getLeadStatusLogs()
    {
        return $this->leadStatusLogs;
    }

    /**
     * @param LeadStatusLog[] $leadStatusLogs
     */
    public function setLeadStatusLogs($leadStatusLogs)
    {
        $this->leadStatusLogs = $leadStatusLogs;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    public static function getTypes()
    {
        return [
            'CX Connect',
            'RFI',
            'RFP',
            'Tender'
        ];
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }
}

