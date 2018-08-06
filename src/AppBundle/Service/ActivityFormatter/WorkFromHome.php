<?php
/**
 * Created by PhpStorm.
 * User: Lixing
 * Date: 30/4/18
 * Time: 7:01 PM
 */

namespace AppBundle\Service\ActivityFormatter;

use AppBundle\Entity\LogEntryInterface;
use AppBundle\Service\ActivityLog\EntityFormatter\AbstractFormatter;
use AppBundle\Service\ActivityLog\EntityFormatter\FormatterInterface;
use Doctrine\ORM\EntityManagerInterface;

class WorkFromHome extends AbstractFormatter implements FormatterInterface
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
            $data = $log->getData();
            $result['message'] = 'A new work from home profile has been created.';
            $result['message'] .= '<br /><br />';

            $text = '';

            foreach ($data as $field => $value) {
                $value = $this->normalizeValue($field, $value, 'H:i');
                $field = strtoupper($this->fromCamelCase($field));
                $text .= sprintf('<dd>Field "<strong>%s</strong>" was created: %s</dd>', $field, $value);
            }
            $result['message'] .= $text;

        } else if ($log->isRemove()) {
            $result['message'] = sprintf('One of the <strong><span class="font-green-jungle">"%s"</span></strong> profiles was removed.', $log->getName());
            $data = $log->getData();
            $text = '';
            foreach ($data as $field => $value) {
                $value = $this->normalizeValue($field, $value, 'd M Y');
                $field = $this->fromCamelCase($field);
                $text .= sprintf('<dd><strong>%s</strong>: %s</dd>', $field, $value);
            }
            $result['message'] .= $text;
        } else if ($log->isUpdate()) {
            $result['message'] = '<dl><dt>Details for the work from home profile have been updated.</dt>%s</dl>';
            $data = $log->getData();
            $oldData = $log->getOldData();

            $text = '';
            foreach ($data as $field => $value) {
                $value = $this->normalizeValue($field, $value, 'H:i');
                if ($value === '') {
                    $value = 'nil';
                }

                if (array_key_exists($field, $oldData)) {
                    $oldValue = $this->normalizeValue($field, $oldData[$field]);
                    $subText = sprintf('from <strong>%s</strong> to <strong>%s</strong>', $oldValue, $value);
                } else {
                    $subText = sprintf('to <strong>%s</strong>', $value);
                }
                $field = $this->fromCamelCase($field);
                $text .= sprintf('<dd>Field "<b>%s</b>" was changed: %s</dd>', $field, $subText);
            }

            $result['message'] = sprintf($result['message'], $text);
        } else {
            $result['message'] = "Undefined action: {$log->getAction()}.";
        }

        return $result;
    }

    public function getWorkFromHome($id)
    {
        /** @var EntityManagerInterface $manger */
        $workFromHome = $this->manager->getRepository('AppBundle:WorkFromHome')->find($id);

        if ($workFromHome) {
            return $workFromHome;
        }

        return null;
    }
}