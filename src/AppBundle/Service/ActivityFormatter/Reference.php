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

class Reference extends AbstractFormatter implements FormatterInterface
{

    public function setEntityManger(EntityManagerInterface $manager)
    {
        parent::setEntityManager($manager);
    }

    /**
     * @param LogEntryInterface $log
     *
     * @return array
     */
    public function format(LogEntryInterface $log)
    {
        $result = $log->toArray();


        if ($log->isCreate()) {
            $result['message'] = sprintf(
                'One of the <strong><span class="font-green-jungle">"%ss"</span></strong> was created.',
                $log->getName()
            );

            $result['message'] .= '<br /><br />';
            $data = $log->getData();
            $text = '';
            foreach ($data as $field => $value) {
                if ($field == 'cessationReason' && $data['type'] == \AppBundle\Entity\Reference::TYPE_CURRENT) {
                   continue;
                }

                if ($field == 'type') {
                    continue;
                }

                $value = $this->normalizeValue($field, $value);
                $field = $this->fromCamelCase($field);
                $text .= sprintf('<dd>Field "<strong>%s</strong>" was created: %s</dd>', $field, $value);
            }
            $result['message'] .= $text;

        } else if ($log->isRemove()) {
            $result['message'] = sprintf(
                'One of the <strong><span class="font-red-flamingo">"%ss"</span></strong> was removed.',
                $log->getName()
            );
        } else if ($log->isUpdate()) {
            $result['message'] = '<dl><dt>One of the <strong><span class="font-yellow-gold">"%ss"</span></strong> has been updated.</dt>%s</dl>';
            $data = $log->getData();
            $oldData = $log->getOldData();

            $text = '';
            foreach ($data as $field => $value) {
                $value = $this->normalizeValue($field, $value);

                if (array_key_exists($field, $oldData)) {
                    $oldValue = $this->normalizeValue($field, $oldData[$field]);
                    $subText = sprintf('from "<b>%s</b>" to "<b>%s</b>".', $oldValue, $value);
                } else {
                    $subText = sprintf('to "<b>%s</b>".', $value);
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