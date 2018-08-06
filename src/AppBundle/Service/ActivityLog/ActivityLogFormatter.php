<?php

namespace AppBundle\Service\ActivityLog;

use AppBundle\Entity\LogEntry;
use AppBundle\Entity\LogEntryInterface;
use AppBundle\Service\ActivityLog\EntityFormatter\FormatterInterface;
use AppBundle\Service\ActivityLog\EntityFormatter\UniversalFormatter;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ActivityLogFormatter
 * @package AppBundle\Service\ActivityLog
 */
class ActivityLogFormatter
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var array
     */
    private $customFormatters;

    /**
     * ActivityLogFormatter constructor.
     *
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $manager
     */
    public function __construct(LoggerInterface $logger, EntityManagerInterface $manager)
    {
        $this->logger = $logger;
        $this->manager = $manager;
        $this->customFormatters = [];
    }

    /**
     * @param FormatterInterface $formatter
     * @param string $entity
     */
    public function addFormatter($formatter, $entity)
    {
        $implements = in_array(
            'AppBundle\Service\ActivityLog\EntityFormatter\FormatterInterface',
            class_implements($formatter),
            true
        );

        if ($implements) {
            $formatter->setEntityManger($this->manager);
            $this->customFormatters[$entity] = $formatter;
        }
    }

    /**
     * @param array|LogEntry[] $logs
     * @return array
     */
    public function format(array $logs)
    {
        $result = [];
        foreach ($logs as $log) {
            $result[] = $this->getEntryFormatter($log)->format($log);
        }

        return $result;
    }

    /**
     * @param LogEntryInterface|LogEntry $logEntry
     * @return FormatterInterface
     */
    private function getEntryFormatter(LogEntryInterface $logEntry)
    {
        $className = substr(strrchr(rtrim($logEntry->getObjectClass(), '\\'), '\\'), 1);

        $formatter = $this->getCustomFormatter($className);

        if (array_key_exists($className, $this->customFormatters)) {
            $formatter = $this->customFormatters[$className];
        }

        // Support fully-qualified class names
        if (!$formatter) {
            $this->logger->warning("For entity {$logEntry->getObjectClass()} don't implemented Activity Log Formatter.");
            $formatter = new UniversalFormatter();
        }

        return $formatter;
    }

    /**
     * @param string $className
     * @return FormatterInterface|null
     */
    private function getCustomFormatter($className)
    {
        $formatter = null;

        if (array_key_exists($className, $this->customFormatters)) {
            $formatter = $this->customFormatters[$className];
        }

        return $formatter;
    }
}
