<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientRepository")
 */
class Client
{
    const STATUS_OPEN = 'Open';
    const STATUS_CLOSE_NOT_SUBMIT = 'Closed - Did not submit lead';
    const STATUS_CLOSE_LEAD_SUBMIT = 'Closed - Submitted lead';
    const STATUS_CLOSE_NO_FURTHER_CONTACT = 'Closed - no further contact received';

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
     * @ORM\Column(name="firstName", type="string", length=50, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=50, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=50, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=50, nullable=true)
     */
    private $mobile;

    /**
     * @var string
     * @ORM\Column(name="job_title", type="string", length=50, nullable=true)
     */
    private $jobTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="company", type="string", length=50, nullable=true)
     */
    private $company;

    /**
     * @var string
     * @ORM\Column(name="status", type="string", length=50, length=255)
     */
    private $status;

    /**
     * @var Lead[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Lead", mappedBy="client")
     */
    private $leads;

    /**
     * @var ClientNote[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ClientNote", mappedBy="client", cascade={"persist", "remove"})
     */
    private $clientNotes;

    public function __construct()
    {
        $this->status = self::STATUS_OPEN;
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Client
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Client
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Client
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
     * Set phone
     *
     * @param string $phone
     *
     * @return Client
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return Client
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set company
     *
     * @param string $company
     *
     * @return Client
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @return ClientNote[]
     */
    public function getClientNotes()
    {
        return $this->clientNotes;
    }

    /**
     * @param ClientNote[] $clientNotes
     *
     * @return Client
     */
    public function setClientNotes($clientNotes)
    {
        $this->clientNotes = $clientNotes;

        return $this;
    }

    /**
     * @param ClientNote $clientNote
     */
    public function addClientNote($clientNote)
    {
        $clientNote->setClient($this);
        $this->clientNotes[] = $clientNote;
    }

    public function getName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function __toString()
    {
        return $this->getName();
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

    public static function getStatuses()
    {
        return [
            self::STATUS_OPEN,
            self::STATUS_CLOSE_LEAD_SUBMIT,
            self::STATUS_CLOSE_NO_FURTHER_CONTACT,
            self::STATUS_CLOSE_NOT_SUBMIT
        ];
    }

    /**
     * @return string
     */
    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * @param string $jobTitle
     */
    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;
    }

    /**
     * @return Lead[]
     */
    public function getLeads()
    {
        return $this->leads;
    }

    /**
     * @param Lead[] $leads
     */
    public function setLeads($leads)
    {
        $this->leads = $leads;
    }

    public function addLead(Lead $lead)
    {
        $lead->setClient($this);
        $this->leads[] = $lead;
    }

    public function removeLead(Lead $lead)
    {
        $this->leads->removeElement($lead);
        $lead->setClient(null);
    }
}

