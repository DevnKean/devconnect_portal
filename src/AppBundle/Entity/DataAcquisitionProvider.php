<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\StringableInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * DataAcquisitionProvider
 *
 * @ORM\Table(name="data_acquisition_provider")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DataAcquisitionProviderRepository")
 * @Gedmo\Loggable(logEntryClass="AppBundle\Entity\LogEntry")
 */
class DataAcquisitionProvider implements StringableInterface
{
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
     * @ORM\Column(name="company", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $company;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $website;

    /**
     * @var Supplier
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="dataAcquisitionProviders")
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
     * Set company
     *
     * @param string $company
     *
     * @return DataAcquisitionProvider
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
     * Set website
     *
     * @param string $website
     *
     * @return DataAcquisitionProvider
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
     * @inheritDoc
     */
    public function toString()
    {
        return Profile::PROFILE_DATA_ACQUISITION_PROVIDER;
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

