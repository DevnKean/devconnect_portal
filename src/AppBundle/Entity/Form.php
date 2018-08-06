<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Form
 *
 * @ORM\Table(name="form")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FormRepository")
 */
class Form
{
    const ID_BUSINESS_NAME = 123;
    const ID_CONTACT_NAME = 21;
    const ID_CONTACT_EMAIL = 23;
    const ID_CONTACT_PHONE = 25;

    const ID_TERMS_OF_SERVICE = 26;
    const ID_TERMS_AND_CONDITIONS = 189;
    const ID_PLEASE_TELL_US = 153;
    const ID_SURVEY_1 = 141;
    const ID_SURVEY_2 = 154;
    const ID_OUTSOURCER_ATTRIBUTES = 158;
    const ID_SURVEY_RANK = 145;
    const ID_HEART_ABOUT = 27;
    const ID_CAMPAIGN_LENGTH = 70;
    const ID_ESTIMATE_TYPE = 93;
    const ID_OPTION_HEADCOUNT = 94;
    const ID_OPTION_INBOUND_VOLUMES = 10;
    const ID_OPTION_OUTBOUND_CALL = 96;
    const ID_UNIQUE_ID = 191;

    const SOURCE_CX_CENTRAL = 'CX Central';
    const SOURCE_CX_CONNECT = 'CX Connect';
    const SOURCE_CX_CONNECT_ADMIN = 'CX Connect Admin';
    const SOURCE_CX_CONNECT_JOIN = 'CX Connect Join';

    static $excludeQuestionIds = [
        self::ID_TERMS_OF_SERVICE,
        self::ID_TERMS_AND_CONDITIONS,
        self::ID_SURVEY_1,
        self::ID_SURVEY_2,
        self::ID_OUTSOURCER_ATTRIBUTES,
        self::ID_SURVEY_RANK,
        self::ID_HEART_ABOUT
    ];

    const ID_JOIN_FORM_NAME = 1;
    const ID_JOIN_FORM_BUSINESS_NAME = 4;
    const ID_JOIN_FORM_JOB_TITLE = 5;
    const ID_JOIN_FORM_CONTACT_NUMBER = 2;
    const ID_JOIN_FORM_EMAIL = 3;
    const ID_JOIN_FORM_ADDRESS = 20;
    const ID_JOIN_FORM_ABN_NUMBER = 21;
    const ID_JOIN_FORM_WEBSITE = 6;
    const ID_JOIN_FORM_TOTAL_SEATS = 8;
    const ID_JOIN_FORM_LOCATIONS = 13;
    const ID_JOIN_FORM_YEARS_OF_OPERATIONS = 14;
    const ID_JOIN_FORM_BUSINESS_DIRECTORY = 17;
    const ID_JOIN_FORM_UNIQUE_ID = 18;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Lead", mappedBy="form")
     */
    private $leads;

    /**
     * @var integer
     *
     * @ORM\Column(name="gravity_form_id", type="integer")
     */
    private $gravityFormId;


    /**
     * @ORM\Column(type="text")
     */
    private $rawData;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     * @ORM\Column(name="source", type="string", length=20)
     */
    private $source;


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
     * Set leads
     *
     * @param string $leads
     *
     * @return Form
     */
    public function setLeads($leads)
    {
        $this->leads = $leads;

        return $this;
    }

    /**
     * Get leads
     *
     * @return string
     */
    public function getLeads()
    {
        return $this->leads;
    }

    /**
     * Set gravityFormId
     *
     * @param string $gravityFormId
     *
     * @return Form
     */
    public function setGravityFormId($gravityFormId)
    {
        $this->gravityFormId = $gravityFormId;

        return $this;
    }

    /**
     * Get gravityFormId
     *
     * @return string
     */
    public function getGravityFormId()
    {
        return $this->gravityFormId;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     *
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return mixed
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     * @param mixed $rawData
     */
    public function setRawData($rawData)
    {
        $this->rawData = $rawData;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

}

