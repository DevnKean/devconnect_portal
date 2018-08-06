<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Model Tier
 *
 * @ORM\Table(name="commission_tier")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommissionTierRepository")
 */
class CommissionTier
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
     * @ORM\Column(name="tier_level", type="string", length=20)
     */
    private $tierLevel;

    /**
     * @var int
     *
     * @ORM\Column(name="lower_threshold", type="integer")
     */
    private $lowerThreshold;

    /**
     * @var int
     *
     * @ORM\Column(name="upper_threshold", type="integer", nullable=true)
     */
    private $upperThreshold;

    /**
     * @var string
     *
     * @ORM\Column(name="rate_year_one", type="decimal", precision=10, scale=4)
     */
    private $rateYearOne;

    /**
     * @var string
     *
     * @ORM\Column(name="rate_year_two", type="decimal", precision=10, scale=4)
     */
    private $rateYearTwo;

    /**
     * @var string
     *
     * @ORM\Column(name="rate_year_three", type="decimal", precision=10, scale=4)
     */
    private $rateYearThree;

    /**
     * @var CommissionModel
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CommissionModel", inversedBy="commissionTiers")
     * @ORM\JoinColumn(name="commission_id", referencedColumnName="id")
     */
    private $commissionModel;

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
     * Set tierLevel
     *
     * @param string $tierLevel
     *
     * @return CommissionTier
     */
    public function setTierLevel($tierLevel)
    {
        $this->tierLevel = $tierLevel;

        return $this;
    }

    /**
     * Get tierLevel
     *
     * @return string
     */
    public function getTierLevel()
    {
        return $this->tierLevel;
    }

    /**
     * Set revenueThresholdLower
     *
     * @param integer $lowerThreshold
     *
     * @return CommissionTier
     */
    public function setLowerThreshold($lowerThreshold)
    {
        $this->lowerThreshold = $lowerThreshold;

        return $this;
    }

    /**
     * Get revenueThresholdLower
     *
     * @return int
     */
    public function getLowerThreshold()
    {
        return $this->lowerThreshold;
    }

    /**
     * @return int
     */
    public function getUpperThreshold()
    {
        return $this->upperThreshold;
    }

    /**
     * @param int $upperThreshold
     */
    public function setUpperThreshold($upperThreshold)
    {
        $this->upperThreshold = $upperThreshold;
    }

    /**
     * Set commissionYearOne
     *
     * @param string $rateYearOne
     *
     * @return CommissionTier
     */
    public function setRateYearOne($rateYearOne)
    {
        $this->rateYearOne = $rateYearOne;

        return $this;
    }

    /**
     * Get commissionYearOne
     *
     * @return string
     */
    public function getRateYearOne()
    {
        return $this->rateYearOne;
    }

    /**
     * Set commissionYearTwo
     *
     * @param string $rateYearTwo
     *
     * @return CommissionTier
     */
    public function setRateYearTwo($rateYearTwo)
    {
        $this->rateYearTwo = $rateYearTwo;

        return $this;
    }

    /**
     * Get commissionYearTwo
     *
     * @return string
     */
    public function getRateYearTwo()
    {
        return $this->rateYearTwo;
    }

    /**
     * Set commissionYearThree
     *
     * @param string $rateYearThree
     *
     * @return CommissionTier
     */
    public function setRateYearThree($rateYearThree)
    {
        $this->rateYearThree = $rateYearThree;

        return $this;
    }

    /**
     * Get commissionYearThree
     *
     * @return string
     */
    public function getRateYearThree()
    {
        return $this->rateYearThree;
    }

    /**
     * @return mixed
     */
    public function getCommissionModel()
    {
        return $this->commissionModel;
    }

    /**
     * @param mixed $commissionModel
     */
    public function setCommissionModel($commissionModel)
    {
        $this->commissionModel = $commissionModel;
    }

}

