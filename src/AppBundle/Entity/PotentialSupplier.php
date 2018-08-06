<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PotentialSupplier
 *
 * @ORM\Table(name="potential_supplier")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PotentialSupplierRepository")
 */
class PotentialSupplier
{
    const STATUS_ACTIONED = 'Actioned';
    const STATUS_POTENTIAL = 'Potential';
    const STATUS_DELETED = 'Deleted';

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
     * @ORM\Column(name="prefix", type="string", length=50, nullable=true)
     */
    private $prefix;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="job_title", type="string", length=255)
     */
    private $jobTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_number", type="string", length=50)
     */
    private $contactNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="business_name", type="string", length=255)
     */
    private $businessName;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="abn_number", type="string", length=100)
     */
    private $abnNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255)
     */
    private $website;

    /**
     * @var string
     *
     * @ORM\Column(name="total_seats", type="string", length=50)
     */
    private $totalSeats;

    /**
     * @var array
     *
     * @ORM\Column(name="locations", type="json_array")
     */
    private $locations;

    /**
     * @var string
     *
     * @ORM\Column(name="years_of_operations", type="string", length=100)
     */
    private $yearsOfOperations;

    /**
     * @var string
     *
     * @ORM\Column(name="business_directory", type="string", length=100)
     */
    private $businessDirectory;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=50)
     */
    private $status;

    /**
     * @var string
     * @ORM\Column(name="username", type="string", length=50)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="initial_password", type="string", length=50)
     */
    private $initialPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="unique_id", type="string", length=100)
     */
    private $uniqueID;

    /**
     * @var Supplier
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="potentialSupplier")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    private $supplier;

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
     * Set prefix
     *
     * @param string $prefix
     *
     * @return PotentialSupplier
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Get prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return PotentialSupplier
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
     * @return PotentialSupplier
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
     * Set jobTitle
     *
     * @param string $jobTitle
     *
     * @return PotentialSupplier
     */
    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    /**
     * Get jobTitle
     *
     * @return string
     */
    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * Set contactNumber
     *
     * @param string $contactNumber
     *
     * @return PotentialSupplier
     */
    public function setContactNumber($contactNumber)
    {
        $this->contactNumber = $contactNumber;

        return $this;
    }

    /**
     * Get contactNumber
     *
     * @return string
     */
    public function getContactNumber()
    {
        return $this->contactNumber;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return PotentialSupplier
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
     * Set businessName
     *
     * @param string $businessName
     *
     * @return PotentialSupplier
     */
    public function setBusinessName($businessName)
    {
        $this->businessName = $businessName;

        return $this;
    }

    /**
     * Get businessName
     *
     * @return string
     */
    public function getBusinessName()
    {
        return $this->businessName;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return PotentialSupplier
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set abnNumber
     *
     * @param string $abnNumber
     *
     * @return PotentialSupplier
     */
    public function setAbnNumber($abnNumber)
    {
        $this->abnNumber = $abnNumber;

        return $this;
    }

    /**
     * Get abnNumber
     *
     * @return string
     */
    public function getAbnNumber()
    {
        return $this->abnNumber;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return PotentialSupplier
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set totalSeats
     *
     * @param string $totalSeats
     *
     * @return PotentialSupplier
     */
    public function setTotalSeats($totalSeats)
    {
        $this->totalSeats = $totalSeats;

        return $this;
    }

    /**
     * Get totalSeats
     *
     * @return string
     */
    public function getTotalSeats()
    {
        return $this->totalSeats;
    }

    /**
     * Set locations
     *
     * @param array $locations
     *
     * @return PotentialSupplier
     */
    public function setLocations($locations)
    {
        $this->locations = $locations;

        return $this;
    }

    /**
     * Get locations
     *
     * @return array
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * Set yearsOfOperations
     *
     * @param string $yearsOfOperations
     *
     * @return PotentialSupplier
     */
    public function setYearsOfOperations($yearsOfOperations)
    {
        $this->yearsOfOperations = $yearsOfOperations;

        return $this;
    }

    /**
     * Get yearsOfOperations
     *
     * @return string
     */
    public function getYearsOfOperations()
    {
        return $this->yearsOfOperations;
    }

    /**
     * Set businessDirectory
     *
     * @param string $businessDirectory
     *
     * @return PotentialSupplier
     */
    public function setBusinessDirectory($businessDirectory)
    {
        $this->businessDirectory = $businessDirectory;

        return $this;
    }

    /**
     * Get businessDirectory
     *
     * @return string
     */
    public function getBusinessDirectory()
    {
        return $this->businessDirectory;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set Status
     *
     * @param string $status
     *
     * @return PotentialSupplier
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getInitialPassword()
    {
        return $this->initialPassword;
    }

    /**
     * @param string $initialPassword
     */
    public function setInitialPassword($initialPassword)
    {
        $this->initialPassword = $initialPassword;
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

}

