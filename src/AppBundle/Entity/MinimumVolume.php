<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\StringableInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MinimumVolume
 *
 * @ORM\Table(name="minimum_volumes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MinimumVolumeRepository")
 * @Gedmo\Loggable(logEntryClass="AppBundle\Entity\LogEntry")
 */
class MinimumVolume implements StringableInterface
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
     * @ORM\Column(name="inboundContacts", type="json_array")
     * @Gedmo\Versioned
     */
    private $inboundContacts;

    /**
     * @var string
     *
     * @ORM\Column(name="headcount", type="json_array")
     * @Gedmo\Versioned
     */
    private $headcount;

    /**
     * @var string
     *
     * @ORM\Column(name="campaignData", type="json_array")
     * @Gedmo\Versioned
     */
    private $campaignData;

    /**
     * @var Supplier
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="minimumVolume")
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
     * Set inboundContacts
     *
     * @param string $inboundContacts
     *
     * @return MinimumVolume
     */
    public function setInboundContacts($inboundContacts)
    {
        $this->inboundContacts = $inboundContacts;

        return $this;
    }

    /**
     * Get inboundContacts
     *
     * @return string
     */
    public function getInboundContacts()
    {
        return $this->inboundContacts;
    }

    /**
     * Set headcount
     *
     * @param string $headcount
     *
     * @return MinimumVolume
     */
    public function setHeadcount($headcount)
    {
        $this->headcount = $headcount;

        return $this;
    }

    /**
     * Get headcount
     *
     * @return string
     */
    public function getHeadcount()
    {
        return $this->headcount;
    }

    /**
     * Set campaignData
     *
     * @param string $campaignData
     *
     * @return MinimumVolume
     */
    public function setCampaignData($campaignData)
    {
        $this->campaignData = $campaignData;

        return $this;
    }

    /**
     * Get campaignData
     *
     * @return string
     */
    public function getCampaignData()
    {
        return $this->campaignData;
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

    public static function getInboundContactsOptions()
    {
        return [
            'Ad hoc (less than 50)',
            '50 - 100',
            '101 - 200',
            '201 - 1,000',
            '1001 - 2,500',
            '2,501 - 5,000',
            '5,001 - 10,000',
            '10,000 +',
        ];
    }

    public static function getHeadcountOptions()
    {
        return [
            'Less than 5 FTE',
            '5 - 10 FTE',
            '11 - 20 FTE',
            '21 - 50 FTE',
            '51 - 100 FTE',
            '101+ FTE',
        ];
    }

    public static function getCampaignDataOptions()
    {
        return [
            '<1,000',
            '1,001 - 2,499',
            '2,500 - 4,999',
            '5,000 - 9,999',
            '10,000 - 24,999',
            '25,000 - 49,999',
            '50,000 - 99,999',
            '100,000 - 249,99',
            '250,000 - 499,999',
            '500,000 - 1,000,000',
            '1,000,000 +',
        ];
    }

    /**
     * @inheritDoc
     */
    public function toString()
    {
        return Profile::PROFILE_MINIMUM_VOLUME;
    }

}

