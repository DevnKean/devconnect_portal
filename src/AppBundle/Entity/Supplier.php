<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\StringableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Supplier
 *
 * @ORM\Table(name="supplier")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SupplierRepository")
 * @Gedmo\Loggable(logEntryClass="AppBundle\Entity\LogEntry")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @UniqueEntity(fields="abnNumber", message="ABN Number already taken")
 */
class Supplier implements StringableInterface
{
    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE = 'active';
    const STATUS_CLOSED = 'closed';
    const STATUS_SUSPENDED = 'suspended';

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
     * @ORM\Column(name="business_name", type="string")
     */
    private $businessName;

    /**
     * @var string
     *
     * @ORM\Column(name="trading_name", type="string")
     */
    private $tradingName;

    /**
     * @var string
     *
     * @ORM\Column(name="abn_number", type="string", unique=true)
     * @Assert\NotBlank(message="ABN Number should not be blank")
     */
    private $abnNumber;

    /**
     * @var string
     * @Assert\Url(
     *     checkDNS = true,
     *     dnsMessage = "The host '{{ value }}' could not be resolved.",
     *     message="Please enter a full URL like https://www.google.com"
     * )
     * @ORM\Column(name="website", type="string", nullable=true)
     */
    private $website;

    /**
     * @var string
     * @Assert\Url(
     *     checkDNS = true,
     *     dnsMessage = "The host '{{ value }}' could not be resolved.",
     *     message="Please enter a full URL like https://www.google.com"
     * )
     * @ORM\Column(name="linkedin", type="string", nullable=true)
     */
    private $linkedin;

    /**
     * @var string
     * @Assert\Url(
     *     checkDNS = true,
     *     dnsMessage = "The host '{{ value }}' could not be resolved.",
     *     message="Please enter a full URL like https://www.google.com"
     * )
     * @ORM\Column(name="twitter", type="string", nullable=true)
     */
    private $twitter;

    /**
     * @var string
     * @Assert\Url(
     *     checkDNS = true,
     *     dnsMessage = "The host '{{ value }}' could not be resolved.",
     *     message="Please enter a full URL like https://www.google.com"
     * )
     * @ORM\Column(name="youtube", type="string", nullable=true)
     */
    private $youtube;

    /**
     * @var string
     * @Assert\Url(
     *     checkDNS = true,
     *     dnsMessage = "The host '{{ value }}' could not be resolved.",
     *     message="Please enter a full URL like https://www.google.com"
     * )
     * @ORM\Column(name="instagram", type="string", nullable=true)
     */
    private $instagram;

    /**
     * @var string
     * @Assert\Url(
     *     checkDNS = true,
     *     dnsMessage = "The host '{{ value }}' could not be resolved.",
     *     message="Please enter a full URL like https://www.google.com"
     * )
     * @ORM\Column(name="snapchat", type="string", nullable=true)
     */
    private $snapchat;

    /**
     * @var string
     * @ORM\Column(name="status", type="string")
     */
    private $status = self::STATUS_PENDING;

    /**
     * @var User[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\User", mappedBy="supplier", cascade={"persist", "remove"})
     */
    private $users;

    /**
     * @var string
     * @ORM\Column(name="address", type="string", nullable=true)
     */
    private $address;

    /**
     * @var Contact[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Contact", mappedBy="supplier", cascade={"persist", "remove"})
     *
     */
    private $contacts;

    /**
     * @var Contract[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Contract", mappedBy="supplier", cascade={"persist", "remove"})
     */
    private $contracts;

    /**
     * @var Location[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Location", mappedBy="supplier", cascade={"persist", "remove"})
     */
    private $locations;

    /**
     * @var Customer[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Customer", mappedBy="supplier", cascade={"persist", "remove"})
     */
    private $customers;

    /**
     * @var Experience[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Experience", mappedBy="supplier", cascade={"persist", "remove"})
     */
    private $experiences;

    /**
     * @var Reference[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Reference", mappedBy="supplier", cascade={"persist", "remove"})
     */
    private $references;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\LeadSupplier", mappedBy="supplier")
     * @ORM\JoinTable(name="suppliers_leads")
     */
    private $allocatedLeads;

    /**
     * @var Commercial
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Commercial", mappedBy="supplier", cascade={"persist", "remove"})
     */
    private $commercial;

    /**
     * @var ChannelSupport[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ChannelSupport", mappedBy="supplier", cascade={"persist", "remove"})
     */
    private $channelSupports;

    /**
     * @var DataAcquisition
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\DataAcquisition", mappedBy="supplier", cascade={"persist", "remove"})
     */
    private $dataAcquisition;

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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var integer
     * @ORM\Column(name="professional_indemnity", type="decimal", precision=10, scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $professionalIndemnity;

    /**
     * @var integer
     * @ORM\Column(name="public_liability", type="decimal", precision=10, scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $publicLiability;

    /**
     * @var SupplierProfile[] | ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SupplierProfile", mappedBy="supplier")
     */
    private $supplierProfiles;

    /**
     * @var Certification[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Certification", mappedBy="supplier", cascade={"persist", "remove"})
     */
    private $certifications;

    /**
     * @var Award[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Award", mappedBy="supplier", cascade={"persist", "remove"})
     */
    private $awards;

    /**
     * @var Technology[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Technology", mappedBy="supplier", cascade={"persist", "remove"})
     */
    private $technologies;

    /**
     * @var SupportFunction[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SupportFunction", mappedBy="supplier", cascade={"persist", "remove"})
     */
    private $supportFunctions;

    /**
     * @var LeadNote[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\LeadNote", mappedBy="supplier", cascade={"persist", "remove"})
     */
    private $leadNotes;

    /**
     * @var MinimumVolume
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\MinimumVolume", mappedBy="supplier", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     */
    private $minimumVolume;

    /**
     * @var AccountNote[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AccountNote", mappedBy="supplier", cascade={"persist", "remove"})
     */
    private $accountNotes;

    /**
     * @var SupplierNote[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SupplierNote", mappedBy="supplier", cascade={"persist", "remove"})
     */
    private $supplierNotes;

    /**
     * @var LeadStatusLog[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\LeadStatusLog", mappedBy="supplier", cascade={"persist", "remove"})
     */
    private $leadStatusLogs;

    /**
     * @var Tender
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Tender", mappedBy="supplier")
     */
    private $tender;

    /**
     * @var PotentialSupplier
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\PotentialSupplier", mappedBy="supplier")
     */
    private $potentialSupplier;

    /**
     * @var WorkFromHome[]| ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\WorkFromHome", mappedBy="supplier", cascade={"persist", "remove"})
     */
    private $workFromHomes;

    /**
     * @var DataAcquisitionProvider[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DataAcquisitionProvider", mappedBy="supplier", cascade={"persist", "remove"})
     */
    private $dataAcquisitionProviders;

    /**
     * Supplier constructor.
     */
    public function __construct()
    {
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid('', true));
        $this->users = new ArrayCollection();
        $this->contracts = new ArrayCollection();
        $this->awards = new ArrayCollection();
        $this->technologies = new ArrayCollection();
        $this->certifications = new ArrayCollection();
        $this->dataAcquisitionProviders = new ArrayCollection();
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
     * @return string
     */
    public function getBusinessName()
    {
        return $this->businessName;
    }

    /**
     * @param string $businessName
     */
    public function setBusinessName($businessName)
    {
        $this->businessName = $businessName;
    }

    /**
     * @return string
     */
    public function getTradingName()
    {
        return $this->tradingName;
    }

    /**
     * @param string $tradingName
     */
    public function setTradingName($tradingName)
    {
        $this->tradingName = $tradingName;
    }

    /**
     * @return string
     */
    public function getAbnNumber()
    {
        return $this->abnNumber;
    }

    /**
     * @param string $abnNumber
     */
    public function setAbnNumber($abnNumber)
    {
        $this->abnNumber = $abnNumber;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return ArrayCollection $users
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }


    /**
     * @return ArrayCollection | Contact[]
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @param Contact[] $contacts
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;
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
     * @return ArrayCollection<Contract>
     */
    public function getContracts()
    {
        return $this->contracts;
    }

    /**
     * @param Contract[] $contracts
     */
    public function setContracts($contracts)
    {
        $this->contracts = $contracts;
    }

    /**
     * @return Contract mixed
     */
    public function getContract()
    {
        return $this->contracts->first();
    }

    /**
     * @param Contract $contract
     */
    public function addContract($contract) {
        $contract->setSupplier($this);
        $this->contracts[] = $contract;
    }

    /**
     * @param Contact $contact
     */
    public function addContact($contact) {
        $contact->setSupplier($this);
        $this->contacts[] = $contact;
    }

    /**
     * @param Contact $contact
     */
    public function removeContact($contact)
    {
        $this->contacts->removeElement($contact);
        $contact->setSupplier(null);
    }
    /**
     * @param User $user
     */
    public function addUser($user) {
        $user->setSupplier($this);
        $this->users[] = $user;
    }

    /**
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param string $website
     */
    public function setWebsite(string $website)
    {
        $this->website = $website;
    }

    /**
     * @return string
     */
    public function getLinkedin()
    {
        return $this->linkedin;
    }

    /**
     * @param string $linkedin
     */
    public function setLinkedin($linkedin)
    {
        $this->linkedin = $linkedin;
    }

    /**
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param string $twitter
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    }

    /**
     * @return string
     */
    public function getYoutube()
    {
        return $this->youtube;
    }

    /**
     * @param string $youtube
     */
    public function setYoutube($youtube)
    {
        $this->youtube = $youtube;
    }

    /**
     * @return string
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * @param string $instagram
     */
    public function setInstagram($instagram)
    {
        $this->instagram = $instagram;
    }

    /**
     * @return string
     */
    public function getSnapchat()
    {
        return $this->snapchat;
    }

    /**
     * @param string $snapchat
     */
    public function setSnapchat($snapchat)
    {
        $this->snapchat = $snapchat;
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
     * @return ArrayCollection
     */
    public function getAllocatedLeads()
    {
        return $this->allocatedLeads;
    }

    /**
     * @param mixed $allocatedLeads
     */
    public function setAllocatedLeads($allocatedLeads)
    {
        $this->allocatedLeads = $allocatedLeads;
    }

    public function removeAllocatedLeads()
    {

    }

    /**
     * @return ArrayCollection | LeadSupplier[]
     */
    public function getCampaigns()
    {
        return $this->allocatedLeads->filter(function (LeadSupplier $leadSupplier) {
           return $leadSupplier->isWon();
        });
    }

    public function getWinningConversion()
    {
        if (!$this->allocatedLeads->count()) {
            return 0;
        }

        return round($this->getCampaigns()->count() / $this->allocatedLeads->count(), 2);
    }

    /**
     * @return Location[] | ArrayCollection
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * @param Location[] $locations
     */
    public function setLocations($locations)
    {
        $this->locations = $locations;
    }

    /**
     * @return Customer[] | ArrayCollection
     */
    public function getCustomers()
    {
        return $this->customers;
    }

    /**
     * @param Customer[] $customers
     */
    public function setCustomers($customers)
    {
        $this->customers = $customers;
    }

    /**
     * @return Experience[] | ArrayCollection
     */
    public function getExperiences()
    {
        return $this->experiences;
    }

    /**
     * @param Experience $experiences
     */
    public function setExperiences($experiences)
    {
        $this->experiences = $experiences;
    }

    /**
     * @return ArrayCollection | Reference[]
     */
    public function getReferences()
    {
        return $this->references;
    }

    /**
     * @param Reference[] | ArrayCollection $references
     */
    public function setReferences($references)
    {
        $this->references = $references;
    }

    /**
     * @param Location $location
     */
    public function addLocation($location) {
        $location->setSupplier($this);
        $this->locations[] = $location;
    }

    /**
     * @param Location $location
     */
    public function removeLocation($location) {
        $this->locations->removeElement($location);
        $location->setSupplier(null);
    }

    /**
     * @param Customer $customer
     */
    public function addCustomer($customer) {
        $customer->setSupplier($this);
        $this->customers[] = $customer;
    }

    /**
     * @param Customer $customer
     */
    public function removeCustomer($customer) {
        $this->locations->removeElement($customer);
        $customer->setSupplier(null);
    }

    /**
     * @param Reference $reference
     */
    public function addReference($reference)
    {
        $reference->setSupplier($this);
        $this->references[] = $reference;
    }

    /**
     * @param Experience $experience
     */
    public function addExperience($experience) {
        $experience->setSupplier($this);
        $this->experiences[] = $experience;
    }

    /**
     * @param Technology $technology
     */
    public function addTechnology($technology) {
        $technology->setSupplier($this);
        $this->technologies[] = $technology;
    }

    /**
     * @param Technology $technology
     */
    public function removeTechnology($technology)
    {
        $this->technologies->removeElement($technology);
        $technology->setSupplier(null);
    }

    /**
     * @param ChannelSupport $channelSupport
     */
    public function addChannelSupport($channelSupport) {
        $channelSupport->setSupplier($this);
        $this->channelSupports[] = $channelSupport;
    }

    /**
     * @param ChannelSupport $channelSupport
     */
    public function removeChannelSupport($channelSupport)
    {
        $this->channelSupports->removeElement($channelSupport);
        $channelSupport->setSupplier(null);
    }

    /**
     * @param Award $award
     */
    public function addAward($award) {
        $award->setSupplier($this);
        $this->awards[] = $award;
    }

    /**
     * @param Award $award
     */
    public function removeAward($award) {
        $this->awards->removeElement($award);
        $award->setSupplier(null);
    }

    /**
     * @param Certification $certification
     */
    public function addCertification($certification) {
        $certification->setSupplier($this);
        $this->certifications[] = $certification;
    }

    /**
     * @param Certification $certification
     */
    public function removeCertification($certification)
    {
        $this->certifications->removeElement($certification);
        $certification->setSupplier(null);
    }

    /**
     * @param SupportFunction $supportFunction
     */
    public function addSupportFunction($supportFunction) {
        $supportFunction->setSupplier($this);
        $this->supportFunctions[] = $supportFunction;
    }

    public function getEarnings()
    {
        $revenue = 0;
        foreach ($this->getCampaigns() as $campaign) {
            $revenue += $campaign->getTotalAmount();
        }


        return $revenue;
    }

    public function getCommissions()
    {
        $commission = 0;
        foreach ($this->getCampaigns() as $campaign) {
            $commission += $campaign->getTotalCommission();
        }

        return $commission;
    }

    /**
     * @return int
     */
    public function getProfessionalIndemnity()
    {
        return $this->professionalIndemnity;
    }

    /**
     * @param int $professionalIndemnity
     *
     * @return Supplier
     */
    public function setProfessionalIndemnity($professionalIndemnity)
    {
        $this->professionalIndemnity = $professionalIndemnity;

        return $this;
    }

    /**
     * @return int
     */
    public function getPublicLiability()
    {
        return $this->publicLiability;
    }

    /**
     * @param int $publicLiability
     *
     * @return Supplier
     */
    public function setPublicLiability($publicLiability)
    {
        $this->publicLiability = $publicLiability;

        return $this;
    }

    /**
     * @return  SupplierProfile[] | ArrayCollection<SupplierProfile>
     */
    public function getSupplierProfiles()
    {
        return $this->supplierProfiles;
    }

    /**
     * @param mixed $supplierProfiles
     *
     * @return Supplier
     */
    public function setSupplierProfiles($supplierProfiles)
    {
        $this->supplierProfiles = $supplierProfiles;

        return $this;
    }

    /**
     * @return Certification[] | ArrayCollection
     */
    public function getCertifications()
    {
        return $this->certifications;
    }

    /**
     * @param Certification[] $certifications
     *
     * @return Supplier
     */
    public function setCertifications($certifications)
    {
        $this->certifications = $certifications;

        return $this;
    }

    /**
     * @return Award[] | ArrayCollection
     */
    public function getAwards()
    {
        return $this->awards;
    }

    /**
     * @param Award[] $awards
     *
     * @return Supplier
     */
    public function setAwards($awards)
    {
        $this->awards = $awards;

        return $this;
    }

    /**
     * @return Technology[] | ArrayCollection
     */
    public function getTechnologies()
    {
        return $this->technologies;
    }

    /**
     * @param Technology[] $technologies
     */
    public function setTechnologies($technologies)
    {
        $this->technologies = $technologies;
    }

    /**
     * @return ChannelSupport[]|ArrayCollection
     */
    public function getChannelSupports()
    {
        return $this->channelSupports;
    }

    /**
     * @param ChannelSupport[]|ArrayCollection $channelSupports
     */
    public function setChannelSupports($channelSupports)
    {
        $this->channelSupports = $channelSupports;
    }

    /**
     * @return SupportFunction[] | ArrayCollection
     */
    public function getSupportFunctions()
    {
        return $this->supportFunctions;
    }

    /**
     * @param SupportFunction[] $supportFunctions
     */
    public function setSupportFunctions($supportFunctions)
    {
        $this->supportFunctions = $supportFunctions;
    }

    public static function getStatues()
    {
        return [
            'Pending' => self::STATUS_PENDING,
            'Active' => self::STATUS_ACTIVE,
            'Closed' => self::STATUS_CLOSED,
            'Suspended' => self::STATUS_SUSPENDED,
        ];
    }

    public function __toString()
    {
        return $this->businessName;
    }

    /**
     * @return mixed
     */
    public function getContractStatus()
    {
        if (!$this->contracts->count()) {
            return Contract::NOT_APPLICABLE;
        }

        return $this->contracts->first()->getStatus();
    }

    /**
     * @return string
     */
    public function getContractDaysLeft()
    {
        if (!$this->contracts->count()) {
            return Contract::NOT_APPLICABLE;
        }

        return $this->contracts->first()->getDaysLeft();
    }

    /**
     * @param $service
     *
     * @return bool
     */
    public function hasService($service)
    {
        $hasService = false;
        if ($service instanceof Service) {
            $serviceName = $service->getName();
        } else {
            $serviceName = $service;
        }

        $this->getContract()->getContractServices()->map(function (ContractService $contractService) use($serviceName, &$hasService) {
            if ($contractService->getService()->getName() == $serviceName) {
                $hasService = true;
            }
        });

        return $hasService;
    }

    /**
     * @param string $profile
     *
     * @return mixed
     */
    public function getProfileStatus($profile)
    {
        foreach ($this->supplierProfiles as $supplierProfile) {
            if ($supplierProfile->getProfile()->getName() == $profile) {
                return $supplierProfile->getStatus();
            }
        }

        return null;
    }

    /**
     * @param $profile
     *
     * @return bool
     */
    public function getProfileIsPending($profile)
    {
        if ($this->getProfileStatus($profile)) {
            return $this->getProfileStatus($profile) === SupplierProfile::STATUS_PENDING;
        }

        return true;
    }



    public function getProfileStatusClass($profile)
    {
        switch ($this->getProfileStatus($profile)) {
            case SupplierProfile::STATUS_PENDING:
                return 'bg-orange';
            case SupplierProfile::STATUS_INCOMPLETE:
                return 'btn-danger';
            case SupplierProfile::STATUS_FEEDBACK:
                return 'bg-purple';
            case SupplierProfile::STATUS_APPROVED:
                return 'btn-success';
            case SupplierProfile::STATUS_OPTIONAL:
                return 'btn-blue';
        }
    }

    public function getProfileStatusBoxClass($profile)
    {
        switch ($this->getProfileStatus($profile)) {
            case SupplierProfile::STATUS_PENDING:
                return 'box-warning';
            case SupplierProfile::STATUS_INCOMPLETE:
                return 'box-danger';
            case SupplierProfile::STATUS_FEEDBACK:
                return 'box-purple';
            case SupplierProfile::STATUS_APPROVED:
                return 'box-success';
            case SupplierProfile::STATUS_OPTIONAL:
                return 'box-blue';

        }
    }

    /**
     * @param $type
     *
     * @return ArrayCollection
     */
    public function getReferencesByType($type)
    {
        return $this->references->filter(function (Reference $reference) use ($type) {
            return $reference->getType() == $type;
        });
    }

    /**
     * @return ArrayCollection
     */
    public function getPastReferences()
    {
        return $this->getReferencesByType(Reference::TYPE_PAST);
    }

    /**
     * @return ArrayCollection
     */
    public function getCurrentReferences()
    {
        return $this->getReferencesByType(Reference::TYPE_CURRENT);
    }

    /**
     * @param $references
     */
    public function setCurrentReferences($references)
    {
        $this->references = $references;
    }

    /**
     * @param $references
     */
    public function setPastReferences($references)
    {
        $this->references = $references;
    }

    /**
     * @return Commercial
     */
    public function getCommercial()
    {
        return $this->commercial;
    }

    /**
     * @param Commercial[]|ArrayCollection $commercial
     */
    public function setCommercial($commercial)
    {
        $this->commercial = $commercial;
    }

    public function getUserIds()
    {
        $userIds = [];
        foreach ($this->users as $user) {
            $userIds[] = $user->getId();
        }
        return $userIds;
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
     * @return MinimumVolume
     */
    public function getMinimumVolume()
    {
        return $this->minimumVolume;
    }

    /**
     * @param MinimumVolume $minimumVolume
     */
    public function setMinimumVolume($minimumVolume)
    {
        $this->minimumVolume = $minimumVolume;
    }

    /**
     * @return AccountNote[]
     */
    public function getAccountNotes()
    {
        return $this->accountNotes;
    }

    /**
     * @param AccountNote[] $accountNotes
     */
    public function setAccountNotes($accountNotes)
    {
        $this->accountNotes = $accountNotes;
    }

    /**
     * @return Tender
     */
    public function getTender()
    {
        return $this->tender;
    }

    /**
     * @param Tender $tender
     */
    public function setTender($tender)
    {
        $this->tender = $tender;
    }

    /**
     * @param $type
     *
     * @return Contact|mixed|null
     */
    public function getContact($type)
    {
        foreach ($this->contacts as $contact) {
            if ($contact->getType() == $type) {
                return $contact;
            }
        }

        return null;
    }

    /**
     * @return Contact|mixed|null
     */
    public function getInvoiceContact()
    {
        return $this->getContact(Contact::TYPE_INVOICE);
    }

    /**
     * @return DataAcquisition
     */
    public function getDataAcquisition()
    {
        return $this->dataAcquisition;
    }

    /**
     * @param DataAcquisition $dataAcquisition
     */
    public function setDataAcquisition($dataAcquisition)
    {
        $this->dataAcquisition = $dataAcquisition;
    }

    /**
     * @return Contact
     */
    public function getLeadContact()
    {
        foreach ($this->contacts as $contact) {
            if ($contact->getType() == Contact::TYPE_LEAD) {
                return $contact;
            }
        }

        return null;
    }

    public function getProfileContact()
    {
        foreach ($this->contacts as $contact) {
            if ($contact->getType() == Contact::TYPE_PROFILE_UPDATE) {
                return $contact;
            }
        }

        return null;
    }

    public function getInvoices()
    {
        $invoices = [];
        foreach ($this->getCampaigns() as $campaign) {
            foreach ($campaign->getSupplierInvoices() as $supplierInvoice) {
                if ($supplierInvoice->getInvoice()) {
                    $invoices[] = $supplierInvoice->getInvoice();
                }

            }
        }

        return $invoices;
    }

    /**
     * @return SupplierNote[]
     */
    public function getSupplierNotes()
    {
        return $this->supplierNotes;
    }

    /**
     * @param SupplierNote[] $supplierNotes
     */
    public function setSupplierNotes($supplierNotes)
    {
        $this->supplierNotes = $supplierNotes;
    }

    /**
     * @param SupplierNote $supplierNote
     */
    public function addSupplierNote($supplierNote)
    {
        $supplierNote->setSupplier($this);

        $this->supplierNotes[] = $supplierNote;
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

    public function getAdmin()
    {
        foreach ($this->users as $user) {
            if ($user->isAdmin()) {
                return $user;
            }
        }

        return null;
    }

    public function getProfileStatusCount()
    {
        $incomplete = 0;
        $pending = 0;
        $feedback = 0;
        $approved = 0;
        foreach ($this->supplierProfiles as $profile) {
            switch ($profile->getStatus()) {
                case SupplierProfile::STATUS_PENDING:
                    $pending++;
                    break;
                case SupplierProfile::STATUS_INCOMPLETE:
                    $incomplete++;
                    break;
                case SupplierProfile::STATUS_FEEDBACK:
                    $feedback++;
                    break;
                case SupplierProfile::STATUS_APPROVED:
                case SupplierProfile::STATUS_OPTIONAL:
                    $approved++;
                    break;
            }
        }

        return compact('incomplete', 'pending', 'feedback', 'approved');
    }

    public function getProfileOverallStatus()
    {
        list($incomplete, $pending, $feedback, $approved) = array_values($this->getProfileStatusCount());

        if ($approved == $this->supplierProfiles->count() && $approved > 0) {
            return SupplierProfile::STATUS_APPROVED;
        }

        if ($incomplete == $this->supplierProfiles->count()) {
            return SupplierProfile::STATUS_INCOMPLETE;
        }

        return SupplierProfile::STATUS_PENDING;
    }

    public function toString()
    {
        return Profile::PROFILE_LEGAL;
    }

    /**
     * @return bool
     */
    public function isApproved()
    {
        if ($this->getContract()) {
            return $this->getContract()->getStatus() == Contract::STATUS_APPROVED;
        }

        return false;
    }

    /**
     * @return ArrayCollection
     */
    public function getProfiles()
    {
        $profiles = [];

        foreach ($this->getSupplierProfiles() as $supplierProfile) {
            $profiles[] = $supplierProfile->getProfile();
        }

        return new ArrayCollection($profiles);
    }

    /**
     * @return PotentialSupplier
     */
    public function getPotentialSupplier()
    {
        return $this->potentialSupplier;
    }

    /**
     * @param PotentialSupplier $potentialSupplier
     */
    public function setPotentialSupplier($potentialSupplier)
    {
        $this->potentialSupplier = $potentialSupplier;
    }

    /**
     * @return WorkFromHome[]| ArrayCollection
     */
    public function getWorkFromHomes()
    {
        return $this->workFromHomes;
    }

    /**
     * @param WorkFromHome[] $workFromHomes
     */
    public function setWorkFromHomes($workFromHomes)
    {
        $this->workFromHomes = $workFromHomes;
    }

    /**
     * @param WorkFromHome $workFromHome
     */
    public function addWorkFromHome($workFromHome)
    {
        $workFromHome->setSupplier($this);

        $this->workFromHomes[] = $workFromHome;
    }

    /**
     * @param WorkFromHome $workFromHome
     */
    public function removeWorkFromHome($workFromHome)
    {
        $this->workFromHomes->removeElement($workFromHome);
        $workFromHome->setSupplier(null);
    }

    public function isOutSourcing()
    {
        return $this->hasService(Service::SERVICE_OUTSOURCING);
    }

    public function isVirtualAssistant()
    {
        return $this->hasService(Service::SERVICE_VIRTUAL_ASSISTANT);
    }

    /**
     * @return DataAcquisitionProvider[]|ArrayCollection
     */
    public function getDataAcquisitionProviders()
    {
        return $this->dataAcquisitionProviders;
    }

    /**
     * @param DataAcquisitionProvider[]|ArrayCollection $dataAcquisitionProviders
     */
    public function setDataAcquisitionProviders($dataAcquisitionProviders)
    {
        $this->dataAcquisitionProviders = $dataAcquisitionProviders;
    }

    /**
     * @param DataAcquisitionProvider $dataAcquisitionProvider
     */
    public function addDataAcquisitionProvider($dataAcquisitionProvider) {
        $dataAcquisitionProvider->setSupplier($this);
        $this->dataAcquisitionProviders[] = $dataAcquisitionProvider;
    }

    /**
     * @param DataAcquisitionProvider $dataAcquisitionProvider
     */
    public function removeDataAcquisitionProvider($dataAcquisitionProvider) {
        $this->dataAcquisitionProviders->removeElement($dataAcquisitionProvider);
        $dataAcquisitionProvider->setSupplier(null);
    }

    /**
     * @param $profile
     *
     * @return Profile
     */
    public function getProfile($profile)
    {
        foreach ($this->supplierProfiles as $supplierProfile) {
            if ($supplierProfile->getProfile()->getName() == $profile) {
                return $supplierProfile->getProfile();
            }
        }

        return null;
    }
}

