<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\StringableInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Reference
 *
 * @ORM\Table(name="reference")
 * @Gedmo\Loggable(logEntryClass="AppBundle\Entity\LogEntry")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReferenceRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Reference implements StringableInterface
{

    const TYPE_CURRENT = 'current';
    const TYPE_PAST = 'past';
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
     * @ORM\Column(name="name", type="string", length=20)
     * @Gedmo\Versioned
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="company_name", type="string", length=20)
     * @Gedmo\Versioned
     */
    private $companyName;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=20)
     * @Gedmo\Versioned
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50)
     * @Assert\Email(message="Email is not valid")
     * @Gedmo\Versioned
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="work_phone", type="string", length=20)
     * @Gedmo\Versioned
     */
    private $workPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile_phone", type="string", length=20)
     * @Gedmo\Versioned
     */
    private $mobilePhone;

    /**
     * @var string
     *
     * @ORM\Column(name="functions", type="json_array")
     * @Gedmo\Versioned
     */
    private $functions;

    /**
     * @var string
     *
     * @ORM\Column(name="cessation_reason", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $cessationReason;

    /**
     * @var Supplier
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="references")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    private $supplier;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=10)
     * @Gedmo\Versioned
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(name="campaign", type="string", length=20)
     * @Gedmo\Versioned
     */
    private $campaign;

    /**
     * @var string
     * @ORM\Column(name="campaign_description", type="text")
     * @Gedmo\Versioned
     */
    private $campaignDescription;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

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
     * Set name
     *
     * @param string $name
     *
     * @return Reference
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
     * Set companyName
     *
     * @param string $companyName
     *
     * @return Reference
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Reference
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Reference
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set workPhone
     *
     * @param string $workPhone
     *
     * @return Reference
     */
    public function setWorkPhone($workPhone)
    {
        $this->workPhone = $workPhone;

        return $this;
    }

    /**
     * Get workPhone
     *
     * @return string
     */
    public function getWorkPhone()
    {
        return $this->workPhone;
    }

    /**
     * Set mobilePhone
     *
     * @param string $mobilePhone
     *
     * @return Reference
     */
    public function setMobilePhone($mobilePhone)
    {
        $this->mobilePhone = $mobilePhone;

        return $this;
    }

    /**
     * Get mobilePhone
     *
     * @return string
     */
    public function getMobilePhone()
    {
        return $this->mobilePhone;
    }

    /**
     * Set functions
     *
     * @param string $functions
     *
     * @return Reference
     */
    public function setFunctions($functions)
    {
        $this->functions = $functions;

        return $this;
    }

    /**
     * Get functions
     *
     * @return string
     */
    public function getFunctions()
    {
        return $this->functions;
    }

    /**
     * Set cessationReason
     *
     * @param string $cessationReason
     *
     * @return Reference
     */
    public function setCessationReason($cessationReason)
    {
        $this->cessationReason = $cessationReason;

        return $this;
    }

    /**
     * Get cessationReason
     *
     * @return string
     */
    public function getCessationReason()
    {
        return $this->cessationReason;
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

    /**
     * @return string
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * @param string $campaign
     */
    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * @return mixed
     */
    public function getCampaignDescription()
    {
        return $this->campaignDescription;
    }

    /**
     * @param mixed $campaignDescription
     */
    public function setCampaignDescription($campaignDescription)
    {
        $this->campaignDescription = $campaignDescription;
    }

    /**
     * @inheritDoc
     */
    public function toString()
    {
        return $this->type == self::TYPE_CURRENT ? Profile::PROFILE_CURRENT_REFERENCE : Profile::PROFILE_PAST_REFERENCE;
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

}

