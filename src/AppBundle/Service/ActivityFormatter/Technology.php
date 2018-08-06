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

class Technology extends AbstractFormatter implements FormatterInterface
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


        if ($log->isCreate()) {
            $result['message'] = sprintf('A new <strong><span class="font-green-jungle">"%s"</span></strong> capability was created.', $log->getName());
            $result['message'] .= '<br /><br />';

            $data = $log->getData();
            $text = '';

            foreach ($data as $field => $value) {
                if ($field == 'type') {
                    continue;
                }
                $value = $this->normalizeValue($field, $value);
                $field = $this->fromCamelCase($field);
                if ($value == '') {
                    $value = 'empty';
                }
                $text .= sprintf('<dd><strong>%s</strong>: %s</dd>', $field, $value);
            }
            $result['message'] .= $text;

        } else if ($log->isRemove()) {
            $result['message'] = sprintf('One of the <strong><span class="font-green-jungle">"%s"</span></strong> was removed.', $log->getName());
            $data = $log->getData();
            $text = '';

            foreach ($data as $field => $value) {
                if ($field == 'type') {
                    continue;
                }
                $value = $this->normalizeValue($field, $value);
                $field = $this->fromCamelCase($field);
                $text .= sprintf('<dd><strong>%s</strong>: %s</dd>', $field, $value);
            }
            $result['message'] .= $text;
        } else if ($log->isUpdate()) {
            $technology = $this->getTechnology($log->getObjectId());
            $result['message'] = '<dl><dt>The <strong><span class="font-yellow-gold">"%s"</span></strong> technology has been updated.</dt>%s</dl>';
            $data = $log->getData();
            $oldData = $log->getOldData();

            $text = '';
            foreach ($data as $field => $value) {
                $value = $this->normalizeValue($field, $value);
                if ($value == '') {
                    $value = 'empty';
                }
                if (array_key_exists($field, $oldData)) {
                    $oldValue = $this->normalizeValue($field, $oldData[$field]);

                    if ($oldValue == '') {
                        $oldValue = 'empty';
                    }
                    $subText = sprintf('from <strong>%s</strong> to <strong>%s</strong>', $oldValue, $value);
                } else {
                    $subText = sprintf('to <strong>%s</strong>', $value);
                }
                $field = $this->fromCamelCase($field);
                $text .= sprintf('<dd>Field "<b>%s</b>" was changed: %s</dd>', $field, $subText);
            }

            $result['message'] = sprintf($result['message'], $technology->getTechnology(), $text);
        } else {
            $result['message'] = "Undefined action: {$log->getAction()}.";
        }

        return $result;
    }

    public function getTechnology($id)
    {
        /** @var EntityManagerInterface $manger */
        $technology = $this->manager->getRepository('AppBundle:Technology')->find($id);

        if ($technology) {
            return $technology;
        }

        return null;
    }
}