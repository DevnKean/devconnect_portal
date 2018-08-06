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

class Location extends AbstractFormatter implements FormatterInterface
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

        $location = $this->getLocation($log->getObjectId());

        if ($log->isCreate()) {
            if ($location) {
                $result['message'] = sprintf('A new location at <strong><span class="font-green-jungle">"%s"</span></strong> was created.<br />', $location->getAddress());
            } else {
                $result['message'] = sprintf('A new location was created.<br />');
            }

            $data = $log->getData();
            $text = '';

            foreach ($data as $field => $value) {
                if (empty($value)) {
                    continue;
                }
                $value = $this->normalizeValue($field, $value, 'H:i');
                $field = $this->fromCamelCase($field);
                $text .= sprintf('<dd>"<strong>%s</strong>": %s</dd>', $field, $value);
            }
            $result['message'] .= $text;

        } else if ($log->isRemove()) {
            $result['message'] = sprintf('The following location was removed.');

            $data = $log->getData();
            $text = '';

            foreach ($data as $field => $value) {
                if (empty($value)) {
                    continue;
                }
                $value = $this->normalizeValue($field, $value, 'H:i');
                $field = $this->fromCamelCase($field);
                $text .= sprintf('<dd>"<strong>%s</strong>": %s</dd>', $field, $value);
            }
            $result['message'] .= $text;
        } else if ($log->isUpdate()) {
            $result['message'] = '<dl><dt>The <strong><span class="font-yellow-gold">"%s"</span></strong> location has been updated.</dt>%s</dl>';
            $data = $log->getData();
            $oldData = $log->getOldData();

            $text = '';
            foreach ($data as $field => $value) {
                if (empty($value)) {
                    continue;
                }
                $value = $this->normalizeValue($field, $value, 'H:i');

                if (array_key_exists($field, $oldData)) {
                    $oldValue = $this->normalizeValue($field, $oldData[$field], 'H:i');
                    $subText = sprintf('from <strong>%s</strong> to <strong>%s</strong>', $oldValue, $value);
                } else {
                    $subText = sprintf('to <strong>%s</strong>', $value);
                }
                $field = $this->fromCamelCase($field);
                $text .= sprintf('<dd>Field "<b>%s</b>" was changed: %s</dd>', $field, $subText);
            }

            $result['message'] = sprintf($result['message'], $location->getAddress(), $text);
        } else {
            $result['message'] = "Undefined action: {$log->getAction()}.";
        }

        return $result;
    }

    public function getLocation($id)
    {
        /** @var EntityManagerInterface $manger */
        $location = $this->manager->getRepository('AppBundle:Location')->find($id);

        if ($location) {
            return $location;
        }

        return null;
    }
}