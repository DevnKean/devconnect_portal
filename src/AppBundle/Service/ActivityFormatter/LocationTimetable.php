<?php
namespace AppBundle\Service\ActivityFormatter;

use AppBundle\Entity\LogEntryInterface;
use AppBundle\Service\ActivityLog\EntityFormatter\AbstractFormatter;
use AppBundle\Service\ActivityLog\EntityFormatter\FormatterInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Created by PhpStorm.
 * User: Lixing
 * Date: 14/11/17
 * Time: 4:57 PM
 */

class LocationTimetable extends AbstractFormatter implements FormatterInterface
{

    public function setEntityManger(EntityManagerInterface $manager)
    {
        parent::setEntityManager($manager);
    }


    /**
     * @param LogEntryInterface $log
     * @return array
     */
    public function format(LogEntryInterface $log)
    {
        $result = $log->toArray();

        $timetable = $this->getLocationTimetable($log->getObjectId());

        if ($log->isCreate()) {
            $result['message'] = sprintf('The timetable for <strong><span class="font-green-jungle"> "%s" </span></strong> has been created.<br />', $log->getName());
            $result['message'] .= '<br />';
            $data = $log->getData();
            $text = '';

            foreach ($data as $field => $value) {
                $value = $this->normalizeValue($field, $value, 'H:i');
                if ($field == 'isOpenWholeDay') {
                    $field = 'Open 24 hours';
                }
                $field = $this->fromCamelCase($field);

                $text .= sprintf('<dd>"<strong>%s</strong>": %s</dd>', $field, $value);
            }
            $result['message'] .= $text;

        } else if ($log->isRemove()) {
            $result['message'] = sprintf('The timetable for <strong><span class="font-green-jungle">"%s"</span></strong> was removed.', $log->getName());

            $data = $log->getData();
            $text = '';

            foreach ($data as $field => $value) {
                $value = $this->normalizeValue($field, $value, 'H:i');
                $field = $this->fromCamelCase($field);
                $text .= sprintf('<dd>"<strong>%s</strong>": %s</dd>', $field, $value);
            }
            $result['message'] .= $text;
        } else if ($log->isUpdate()) {
            $result['message'] = '<dl><dt>The timetable for <strong><span class="font-yellow-gold">"%s"</span></strong> has been updated.</dt>%s</dl>';
            $data = $log->getData();
            $oldData = $log->getOldData();

            $text = '';
            foreach ($data as $field => $value) {
                $value = $this->normalizeValue($field, $value, 'H:i');
                if (empty($value)) {
                    $value = 'Empty';
                }
                if (array_key_exists($field, $oldData)) {
                    $oldValue = $this->normalizeValue($field, $oldData[$field], 'H:i');
                    if (empty($oldValue)) {
                        $oldValue = 'Empty';
                    }
                    $subText = sprintf('from <strong>%s</strong> to <strong>%s</strong>', $oldValue, $value);
                } else {
                    $subText = sprintf('to <strong>%s</strong>', $value);
                }
                if ($field == 'isOpenWholeDay') {
                    $field = 'Open 24 hours';
                }
                $field = $this->fromCamelCase($field);

                $text .= sprintf('<dd>The "<strong>%s \'s %s</strong>"  has been changed: %s</dd>', $timetable->getOpenDay(), $field, $subText);
            }

            $result['message'] = sprintf($result['message'], $timetable->getLocation()->getFullAddress(), $text);
        } else {
            $result['message'] = "Undefined action: {$log->getAction()}.";
        }

        return $result;
    }

    /**
     * @param $id
     *
     * @return \AppBundle\Entity\LocationTimetable|null|object
     */
    public function getLocationTimetable($id)
    {
        /** @var EntityManagerInterface $manger */
        $timetable = $this->manager->getRepository('AppBundle:LocationTimetable')->find($id);

        if ($timetable) {
            return $timetable;
        }

        return null;
    }
}