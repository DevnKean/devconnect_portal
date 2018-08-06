<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Service
 *
 * @ORM\Table(name="service")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ServiceRepository")
 */
class Service
{
    const SERVICE_OUTSOURCING = 'Outsourcing';
    const SERVICE_VIRTUAL_ASSISTANT = 'Virtual Assistant';
    const SERVICE_RECEPTION_SERVICES = 'Reception Services';
    const SERVICE_CONSULTANTS = 'Consultants';
    const SERVICE_TECHNOLOGY = 'Technology';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=20)
     */
    private $name;

    /**
     * @var Profile[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Profile", mappedBy="service", cascade={"persist", "remove"})
     * @ORM\OrderBy({"order" = "ASC"})
     */
    private $profiles;

    /**
     * @var Contract[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ContractService", mappedBy="service")
     */
    private $allocatedContracts;

    /**
     * @var Lead
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Lead", mappedBy="service")
     */
    private $leads;

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
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return Profile[] | ArrayCollection
     */
    public function getProfiles()
    {
        return $this->profiles;
    }

    /**
     * @param Profile[] $profiles
     */
    public function setProfiles($profiles)
    {
        $this->profiles = $profiles;
    }

    /**
     * @return Contract[]
     */
    public function getAllocatedContracts()
    {
        return $this->allocatedContracts;
    }

    /**
     * @param Contract[] $allocatedContracts
     */
    public function setAllocatedContracts($allocatedContracts)
    {
        $this->allocatedContracts = $allocatedContracts;
    }

    public function __toString()
    {
        return ucfirst($this->name);
    }

    public static function getServices()
    {
        return [
            'Outsourcing',
            'Consulting'
        ];
    }

    /**
     * @param Profile $profile
     */
    public function addProfile($profile)
    {
        $profile->setService($this);
        $this->profiles[] = $profile;
    }

    public static function getFunctions()
    {
        return [
            'Customer Service - Inbound only',
            'Customer Service - Outbound only',
            'Customer Service - blended',
            'Sales - Inbound',
            'Sales - Blended',
            'Sales - Outbound (cold calls)',
            'Sales - Outbound (warm calls)',
            'Sales - Outbound (hot calls)',
            'Technical Support',
            'Collections',
            'Surveys',
            'Emergency/Roadside support',
            'Live Chat',
            'Video Chat',
            'Fundraising',
            'Lead Generation',
            'Appointment Setting'
        ];
    }

    /**
     * @return Lead
     */
    public function getLeads()
    {
        return $this->leads;
    }

    /**
     * @param Lead $leads
     */
    public function setLeads($leads)
    {
        $this->leads = $leads;
    }

}

