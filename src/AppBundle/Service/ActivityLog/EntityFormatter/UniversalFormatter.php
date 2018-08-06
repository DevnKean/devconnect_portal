<?php

namespace AppBundle\Service\ActivityLog\EntityFormatter;

use AppBundle\Entity\LogEntry;
use AppBundle\Entity\LogEntryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UniversalFormatter
 * @package AppBundle\Service\ActivityLog\EntityFormatter
 */
class UniversalFormatter extends AbstractFormatter
{

    public function setEntityManger(EntityManagerInterface $manager)
    {
        parent::setEntityManager($manager);
    }

    /**
     * @param LogEntryInterface|LogEntry $log
     * @return array
     */
    public function format(LogEntryInterface $log)
    {
        $result = $log->toArray();

        $name = substr(strrchr(rtrim($log->getObjectClass(), '\\'), '\\'), 1);
        if ($log->isCreate()) {
            $result['message'] = sprintf('The entity <b>%s (%s)</b> was created.', $log->getName(), $name);
        } else if ($log->isRemove()) {
            $result['message'] = sprintf('The entity <b>%s (%s)</b> was removed.', $log->getName(), $name);
        } else if ($log->isUpdate()) {
            $result['message'] = sprintf(
                'The entity <b>%s (%s)</b> was updated.<br><b>Prev. data:</b> %s<br><b>New data:</b> %s',
                $log->getName(),
                $name,
                $this->toComment($log->getData()),
                $this->toComment($log->getOldData())
            );
        } else {
            $result['message'] = "Undefined action: {$log->getAction()}.";
        }

        return $result;
    }
}
