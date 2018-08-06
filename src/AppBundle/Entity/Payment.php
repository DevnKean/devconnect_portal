<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Payment
 *
 * @ORM\Table(name="payment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PaymentRepository")
 */
class Payment
{
    use TimestampableEntity;

    const STATUS_SUCCESS = 'Success';
    const STATUS_PENDING = 'Pending';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=2)
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="paid_at", type="datetime")
     */
    private $paidAt;

    /**
     * @var Invoice[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Invoice", inversedBy="payments")
     * @ORM\JoinTable(name="invoices_payments")
     */
    private $invoices;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=20)
     */
    private $status;


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
     * Set amount
     *
     * @param integer $amount
     *
     * @return Payment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $paidAt
     *
     * @return Payment
     */
    public function setPaidAt($paidAt)
    {
        $this->paidAt = $paidAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getPaidAt()
    {
        return $this->paidAt;
    }

    /**
     * @return Invoice[]
     */
    public function getInvoices()
    {
        return $this->invoices;
    }

    /**
     * @param Invoice $invoices
     */
    public function setInvoices($invoices)
    {
        $this->invoices = $invoices;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Payment
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

    public static function getPaymentStatus()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_SUCCESS
        ];
    }
}

