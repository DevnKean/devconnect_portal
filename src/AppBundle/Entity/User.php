<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use ThemeBundle\Model\UserInterface as ThemeUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable, ThemeUser
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
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank(message="Username should not be blank");
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @Assert\NotBlank(message="Password should not be blank", groups={"create"})
     * @Assert\Length(max=4096)
     */
    private $plainPassword;


    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank(message="Email should not be blank");
     * @Assert\Email(message="Email is not valid")
     */
    private $email;

    /**
     * @ORM\Column(name="first_name", type="string", length=60)
     * @Assert\NotBlank(message="First Name should not be blank");
     */
    private $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=60)
     * @Assert\NotBlank(message="Last Name should not be blank");
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(name="job_title", type="string", length=100, nullable=true)
     */
    private $jobTitle;

    /**
     * @var string
     * @ORM\Column(name="contact_phone", type="string", length=100, nullable=true)
     */
    private $contactPhone;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles;

    /**
     * @var \DateTime
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @var \DateTime
     * @ORM\Column(name="login_sent_at", type="datetime", nullable=true)
     */
    private $loginSentAt;

    /**
     * Random string sent to the user email address in order to verify it.
     *
     * @var string
     * @ORM\Column(name="confirmation_token", type="string", nullable=true)
     */
    protected $confirmationToken;

    /**
     * @var \DateTime
     * @ORM\Column(name="password_requested_at", type="datetime", nullable=true)
     */
    protected $passwordRequestedAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var Supplier
     * @ORM\ManyToOne(targetEntity="Supplier", inversedBy="users")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    private $supplier;

    /**
     * @var Communication[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Communication", mappedBy="user")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $communications;

    /**
     * @var \DateTime;
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid('', true));
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }


    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getRoles()
    {

        return empty($this->roles) ? $this->roles: array_unique($this->roles);
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return Supplier
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * @param \AppBundle\Entity\Supplier $supplier
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
    }

    /**
     * @inheritDoc
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->email,
            $this->isActive
            // see section on salt below
            // $this->salt,
        ));
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->email,
            $this->isActive
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    public function getAvatar()
    {
        // TODO: Implement getAvatar() method.
    }

    public function getName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getMemberSince()
    {
        return $this->createdAt;
    }

    public function isOnline()
    {
        return true;
    }

    public function getIdentifier()
    {
        return $this->id;
    }

    public function getTitle()
    {
        // TODO: Implement getTitle() method.
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getRoleName($role)
    {
        switch ($role) {
            case 'ROLE_SUPER_ADMIN':
                return 'Super Admin';
            case 'ROLE_ADMIN':
                return 'Supplier Admin';
            default:
                if (preg_match('/^ROLE_(.*)$/', $role, $matches)) {
                    return ucwords(strtolower(str_replace('_', ' ', $matches[1])));
                }

                return null;
        }
    }

    public function isSuperAdmin()
    {
        return in_array('ROLE_SUPER_ADMIN', $this->getRoles());
    }

    public function isAdmin()
    {
        return in_array('ROLE_ADMIN', $this->getRoles());
    }

    /**
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime $lastLogin
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * @return string
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * @param string $confirmationToken
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
    }

    /**
     * @return \DateTime
     */
    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }

    /**
     * @param \DateTime $passwordRequestedAt
     */
    public function setPasswordRequestedAt($passwordRequestedAt)
    {
        $this->passwordRequestedAt = $passwordRequestedAt;
    }

    public function __toString()
    {
        return $this->username;
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
     * @return string
     */
    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    /**
     * @param string $contactPhone
     */
    public function setContactPhone($contactPhone)
    {
        $this->contactPhone = $contactPhone;
    }

    /**
     * @return \DateTime
     */
    public function getLoginSentAt()
    {
        return $this->loginSentAt;
    }

    /**
     * @param \DateTime $loginSentAt
     */
    public function setLoginSentAt($loginSentAt)
    {
        $this->loginSentAt = $loginSentAt;
    }

    /**
     * @return Communication[]
     */
    public function getCommunications()
    {
        return $this->communications;
    }

    /**
     * @param Communication[] $communications
     */
    public function setCommunications($communications)
    {
        $this->communications = $communications;
    }

}

