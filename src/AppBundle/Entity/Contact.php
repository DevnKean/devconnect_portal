<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\StringableInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contact
 *
 * @ORM\Table(name="contact")
 * @Gedmo\Loggable(logEntryClass="AppBundle\Entity\LogEntry")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContactRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Contact implements StringableInterface
{
    const TYPE_PRIMARY = 'primary';
    const TYPE_INVOICE = 'invoice';
    const TYPE_LEAD = 'lead';
    const TYPE_PROFILE_UPDATE = 'profile update';

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
     * @ORM\Column(name="first_name", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Gedmo\Versioned
     * @Assert\Email(
     *      message = "The email '{{ value }}' is not a valid email.",
     *      checkMX = true
     * )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="work_phone", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $workPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile_phone", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $mobilePhone;

    /**
     * @var Supplier
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="contacts")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    private $supplier;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $type;

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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Contact
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
     * @return Contact
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
     * @return Contact
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
     * @return Contact
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
     * @return Contact
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
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
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

    public function getName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public static function getTypes(Supplier $supplier)
    {
        if ($supplier->isOutSourcing()) {
            return [
                self::TYPE_PRIMARY,
                self::TYPE_INVOICE,
                self::TYPE_LEAD,
                self::TYPE_PROFILE_UPDATE
            ];
        }

        if ($supplier->isVirtualAssistant()) {
            return [
                self::TYPE_PRIMARY,
            ];
        }

        return [];
    }

    public function toString()
    {
        return Profile::PROFILE_CONTACT;
    }

    public function getDetails()
    {
        return sprintf('%s, %s, %s, %s', $this->getName(), $this->email, $this->workPhone,  $this->mobilePhone);
    }
}

