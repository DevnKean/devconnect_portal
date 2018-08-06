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

class Certification extends AbstractFormatter implements FormatterInterface
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
            $result['message'] = sprintf('One <strong><span class="font-green-jungle">"%s"</span></strong> was created in the Legal profile section.', 'certification');
            $result['message'] .= '<br /><br />';

            $data = $log->getData();
            $text = '';

            foreach ($data as $field => $value) {
                $field = $this->fromCamelCase($field);
                $text .= sprintf('<dd>Field "<strong>%s</strong>": %s</dd>', $field, $value);
            }
            $result['message'] .= $text;

        } else if ($log->isRemove()) {
            $result['message'] = sprintf('One of the <strong><span class="font-green-jungle">"%s"</span></strong> was removed.', 'certification');
            $data = $log->getData();
            $text = '';
            foreach ($data as $field => $value) {
                $field = $this->fromCamelCase($field);
                $text .= sprintf('<dd><strong>%s</strong>: %s</dd>', $field, $value);
            }
            $result['message'] .= $text;
        } else if ($log->isUpdate()) {
            $result['message'] = '<dl><dt>The <strong><span class="font-yellow-gold">"%s"</span></strong> has been updated.</dt>%s</dl>';
            $data = $log->getData();
            $oldData = $log->getOldData();

            $text = '';
            foreach ($data as $field => $value) {
                if (array_key_exists($field, $oldData)) {
                    $oldValue = $this->normalizeValue($field, $oldData[$field]);
                    $subText = sprintf('from <strong>%s</strong> to <strong>%s</strong>', $oldValue, $value);
                } else {
                    $subText = sprintf('to <strong>%s</strong>', $value);
                }
                $field = $this->fromCamelCase($field);
                $text .= sprintf('<dd>Field "<b>%s</b>" was changed: %s</dd>', $field, $subText);
            }

            $result['message'] = sprintf($result['message'], $log->getName(), $text);
        } else {
            $result['message'] = "Undefined action: {$log->getAction()}.";
        }

        return $result;
    }
}