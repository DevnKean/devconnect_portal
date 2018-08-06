<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Profile
 *
 * @ORM\Table(name="profile")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProfileRepository")
 */
class Profile
{
    const PROFILE_CONTACT = 'Contact';
    const PROFILE_LOCATION = 'Location';
    const PROFILE_EXPERIENCE = 'Experience';
    const PROFILE_LEGAL = 'Legal';
    const PROFILE_CURRENT_REFERENCE = 'Current Reference';
    const PROFILE_PAST_REFERENCE = 'Past Reference';
    const PROFILE_CUSTOMER = 'Customers';
    const PROFILE_AWARD = 'Awards';
    const PROFILE_TECHNOLOGY = 'Technology';
    const PROFILE_SUPPORT_FUNCTION = 'Support Function';
    const PROFILE_COMMERCIAL = 'Commercials';
    const PROFILE_MINIMUM_VOLUME = 'Minimum Volumes';
    const PROFILE_CHANNEL_SUPPORT = 'Channel Support';
    const PROFILE_DATA_ACQUISITION = 'Data Acquisition';
    const PROFILE_TENDER = 'Tenders';
    // const PROFILE_WORK_FROM_HOME = '';
    const PROFILE_DATA_ACQUISITION_PROVIDER = 'Data Provider';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Service
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Service", inversedBy="profiles")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     */
    private $service;

    /**
     * @var string
     * @ORM\Column(name="initial_status", type="string", length=100)
     */
    private $initialStatus;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SupplierProfile", mappedBy="profile")
     */
    private $assignedSuppliers;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=100)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="route", type="string", length=50)
     */
    private $route;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=30)
     */
    private $icon;

    /**
     * @var integer
     * @ORM\Column(name="order", type="smallint")
     */
    private $order;

    /**
     * @var string
     * @ORM\Column(name="disabled_text", type="string", nullable=true)
     */
    private $disabledText;

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
     * Set service
     *
     * @param string $service
     *
     * @return Profile
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Profile
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Profile
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return ArrayCollection | Supplier[]
     */
    public function getAssignedSuppliers()
    {
        return $this->assignedSuppliers;
    }

    /**
     * @param mixed $assignedSuppliers
     */
    public function setAssignedSuppliers($assignedSuppliers)
    {
        $this->assignedSuppliers = $assignedSuppliers;
    }

    /**
     * @param Supplier $supplier
     *
     * @return bool
     */
    public function isPending(Supplier $supplier)
    {
        if ($this->getSupplierProfile($supplier)) {
            return $this->getSupplierProfile($supplier)->isPending();
        }

        return true;
    }

    /**
     * @param Supplier $supplier
     *
     * @return null | SupplierProfile
     */
    public function getSupplierProfile(Supplier $supplier)
    {
        foreach ($supplier->getSupplierProfiles() as $supplierProfile) {
            if ($supplierProfile->getProfile() === $this && $supplierProfile->getSupplier() === $supplier) {
                return $supplierProfile;
            }
        }

        return null;
    }

    public function __toString()
    {
        return $this->name;
    }

    public static function getProfileClass($slug)
    {
        switch (self::getProfile($slug)) {
            case self::PROFILE_AWARD:
                return Award::class;
            case self::PROFILE_COMMERCIAL:
                return Commercial::class;
            case self::PROFILE_LEGAL:
                return Certification::class;
            case self::PROFILE_SUPPORT_FUNCTION:
                return SupportFunction::class;
            case self::PROFILE_TECHNOLOGY:
                return Technology::class;
            case self::PROFILE_EXPERIENCE:
                return Experience::class;
            case self::PROFILE_CURRENT_REFERENCE:
            case self::PROFILE_PAST_REFERENCE:
                return Reference::class;
            case self::PROFILE_CUSTOMER:
                return Customer::class;
            case self::PROFILE_LOCATION:
                return Location::class;
            case self::PROFILE_CONTACT:
                return Contact::class;
            case self::PROFILE_CHANNEL_SUPPORT:
                return ChannelSupport::class;
            case self::PROFILE_MINIMUM_VOLUME:
                return MinimumVolume::class;
            case self::PROFILE_TENDER:
                return Tender::class;
            case self::PROFILE_WORK_FROM_HOME:
                return WorkFromHome::class;
        }
    }

    public static function getProfiles()
    {
        return [
            'contact' => self::PROFILE_CONTACT,
            'location' => self::PROFILE_LOCATION,
            'experience' => self::PROFILE_EXPERIENCE,
            'legal' => self::PROFILE_LEGAL,
            'current-reference' => self::PROFILE_CURRENT_REFERENCE,
            'past-reference' => self::PROFILE_PAST_REFERENCE,
            'customer' => self::PROFILE_CUSTOMER,
            'award' => self::PROFILE_AWARD,
            'technology' => self::PROFILE_TECHNOLOGY,
            'support-function' => self::PROFILE_SUPPORT_FUNCTION,
            'commercial' => self::PROFILE_COMMERCIAL,
            'minimum-volumes' => self::PROFILE_MINIMUM_VOLUME,
            'tenders' => self::PROFILE_TENDER,
            'channel-support' => self::PROFILE_CHANNEL_SUPPORT,
            'data-acquisition' => self::PROFILE_DATA_ACQUISITION,
            'data-acquisition-provider' => self::PROFILE_DATA_ACQUISITION_PROVIDER,
            'work-from-home' => self::PROFILE_WORK_FROM_HOME,
        ];
    }

    public static function getProfile($slug)
    {
        return self::getProfiles()[strtolower($slug)];
    }

    /**
     * @return string
     */
    public function getDisabledText()
    {
        return $this->disabledText;
    }

    /**
     * @param string $disabledText
     */
    public function setDisabledText($disabledText)
    {
        $this->disabledText = $disabledText;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return string
     */
    public function getInitialStatus()
    {
        return $this->initialStatus;
    }

    /**
     * @param string $initialStatus
     */
    public function setInitialStatus($initialStatus)
    {
        $this->initialStatus = $initialStatus;
    }

}

