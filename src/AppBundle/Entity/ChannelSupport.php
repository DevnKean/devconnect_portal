<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\StringableInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ChannelSupport
 *
 * @ORM\Table(name="channel_support")
 * @Gedmo\Loggable(logEntryClass="AppBundle\Entity\LogEntry")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChannelSupportRepository")
 */
class ChannelSupport implements StringableInterface
{
    const TYPE_DEFAULT = 'Default';
    const TYPE_CUSTOM = 'Custom';

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
     * @ORM\Column(name="channel", type="string", length=50)
     * @Gedmo\Versioned
     */
    private $channel;

    /**
     * @var string
     *
     * @ORM\Column(name="experience_level", type="string", length=50)
     * @Gedmo\Versioned
     */
    private $experienceLevel;

    /**
     * @var string
     * @ORM\Column(name="type", length=10)
     * @Gedmo\Versioned
     */
    private $type;

    /**
     * @var Supplier
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="channelSupports")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    private $supplier;

    /**
     * ChannelSupport constructor.
     */
    public function __construct()
    {
        $this->type = self::TYPE_CUSTOM;
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
     * Set channel
     *
     * @param string $channel
     *
     * @return ChannelSupport
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Set experienceLevel
     *
     * @param string $experienceLevel
     *
     * @return ChannelSupport
     */
    public function setExperienceLevel($experienceLevel)
    {
        $this->experienceLevel = $experienceLevel;

        return $this;
    }

    /**
     * Get experienceLevel
     *
     * @return string
     */
    public function getExperienceLevel()
    {
        return $this->experienceLevel;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return ChannelSupport
     */
    public function setType($type)
    {
        $this->type = empty($type) ? self::TYPE_CUSTOM : $type;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toString()
    {
        return Profile::PROFILE_CHANNEL_SUPPORT;
    }

    public static function getChannels()
    {
        return [
            'Phone',
            'Live Chat',
            'SMS',
            'Email',
            'Facebook',
            'Twitter',
            'Snapchat',
            'Instagram',
            'Video Chat',
        ];
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
     * @param Supplier $supplier
     *
     * @return array
     */
    public static function getChannelSupports(Supplier $supplier)
    {
        if ($supplier->isOutSourcing()) {
            return [
                'Expert' => 'Expert',
                'Proficient' => 'Proficient',
                'Still learning' => 'Still learning',
                'We don\'t have it yet' => 'We don\'t have it yet'
            ];
        }

        if ($supplier->isVirtualAssistant()) {
            return [
                'Expert' => 'Expert',
                'Proficient' => 'Proficient',
                'Still learning' => 'Still learning',
                'I don\'t support it yet' => 'I don\'t support it yet'
            ];
        }

        return [];
    }

}

