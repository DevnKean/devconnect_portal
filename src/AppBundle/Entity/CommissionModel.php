<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Commission
 *
 * @ORM\Table(name="commission_model")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommissionModelRepository")
 * @UniqueEntity(
 *     fields={"name"},
 *     message="This name is already used"
 * )
 */
class CommissionModel
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
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     */
    private $name;

    /**
     * @var ContractService[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ContractService", mappedBy="commissionModel")
     */
    private $allocatedContractServices;

    /**
     * @var CommissionTier[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CommissionTier", mappedBy="commissionModel", cascade={"persist", "remove"})
     */
    private $commissionTiers;

    /**
     * @var float
     * @ORM\Column(name="flat_rate", type="float", nullable=true)
     */
    private $flatRate;

    /**
     * @var boolean
     */
    private $isFlatRate;

    /**
     * @var File
     * @Assert\File(
     *     maxSize = "2048k",
     *     mimeTypes = {"text/csv", "text/plain", "application/vnd.ms-excel"},
     *     mimeTypesMessage = "Please upload a csv file"
     * )
     */
    private $file;

    /**
     * @var boolean
     */
    private $hasHeader;

    public function __construct()
    {
        $this->commissionTiers = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return CommissionModel
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
     * @return ContractService[]
     */
    public function getAllocatedContractServices()
    {
        return $this->allocatedContractServices;
    }

    /**
     * @param ContractService $allocatedContractServices
     */
    public function setAllocatedContractServices($allocatedContractServices)
    {
        $this->allocatedContractServices = $allocatedContractServices;
    }

    /**
     * @return CommissionTier[]
     */
    public function getCommissionTiers()
    {
        return $this->commissionTiers;
    }

    /**
     * @param CommissionTier[] $commissionTiers
     */
    public function setCommissionTiers($commissionTiers)
    {
        $this->commissionTiers = $commissionTiers;
    }

    /**
     * @param CommissionTier $commissionTier
     */
    public function addCommissionTier($commissionTier)
    {
        $commissionTier->setCommissionModel($this);

        $this->commissionTiers[] = $commissionTier;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return bool
     */
    public function isHasHeader()
    {
        return $this->hasHeader;
    }

    /**
     * @param bool $hasHeader
     */
    public function setHasHeader($hasHeader)
    {
        $this->hasHeader = $hasHeader;
    }

    /**
     * @return float
     */
    public function getFlatRate()
    {
        return $this->flatRate;
    }

    /**
     * @param float $flatRate
     */
    public function setFlatRate($flatRate)
    {
        $this->flatRate = $flatRate;
    }

    /**
     * @return bool
     */
    public function isFlatRate()
    {
        return !empty($this->flatRate) && !$this->commissionTiers->count();
    }

    /**
     * @param bool $isFlatRate
     */
    public function setIsFlatRate($isFlatRate)
    {
        $this->isFlatRate = $isFlatRate;
    }

}

