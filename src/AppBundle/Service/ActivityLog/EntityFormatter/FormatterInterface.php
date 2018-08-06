<?php

namespace AppBundle\Service\ActivityLog\EntityFormatter;

use AppBundle\Entity\LogEntryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Interface FormatterInterface
 * @package AppBundle\Service\ActivityLog\EntityFormatter
 */
interface FormatterInterface
{
    /**
     * @param LogEntryInterface $log
     * @return array
     */
    public function format(LogEntryInterface $log);

    public function setEntityManger(EntityManagerInterface $manager);
}
