<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * SupplierProfile
 *
 * @ORM\Table(name="suppliers_profiles")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SupplierProfileRepository")
 * @UniqueEntity(
 *     fields={"supplier", "profile"},
 *     message="This supplier is already allocated with this profile",
 *     errorPath="supplier"
 * )
 */
class SupplierProfile
{
    const STATUS_PENDING = 'Pending';
    const STATUS_APPROVED = 'Approved';
    const STATUS_INCOMPLETE = 'Incomplete';
    const STATUS_FEEDBACK = 'Feedback';
    const STATUS_OPTIONAL = 'Optional';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="supplierProfiles")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    private $supplier;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Profile", inversedBy="assignedSuppliers")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     */
    private $profile;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=20)
     */
    private $status;

    /**
     * @var boolean
     * @ORM\Column(name="is_disabled", type="boolean")
     */
    private $isDisabled;

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
     * Set status
     *
     * @param string $status
     *
     * @return SupplierProfile
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return Supplier
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * @param mixed $supplier
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
    }

    /**
     * @return Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param mixed $profile
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
    }

    /**
     * @return boolean
     */
    public function getIsDisabled()
    {
        return $this->isDisabled;
    }

    /**
     * @param boolean $isDisabled
     */
    public function setIsDisabled($isDisabled)
    {
        $this->isDisabled = $isDisabled;
    }

    public function isPending()
    {
        return $this->status == static::STATUS_PENDING;
    }

    public function getBadge()
    {
        switch ($this->status) {
            case self::STATUS_INCOMPLETE:
                $color = 'red';
                break;
            case self::STATUS_PENDING:
                $color = 'yellow';
                break;
            case self::STATUS_APPROVED:
                $color = 'green';
                break;
            case self::STATUS_FEEDBACK:
                $color = 'purple';
                break;
            case self::STATUS_OPTIONAL:
                $color = 'blue';
                break;
            default:
                $color = 'red';
        }

        return [
            'status' => $this->status,
            'color' => $color
        ];

    }

    public static function getProfileStatus()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_FEEDBACK,
            self::STATUS_INCOMPLETE,
            self::STATUS_APPROVED,
            self::STATUS_OPTIONAL
        ];
    }

    public function getBadgeColor()
    {
        $badge = $this->getBadge();

        return $badge['color'];
    }
}

