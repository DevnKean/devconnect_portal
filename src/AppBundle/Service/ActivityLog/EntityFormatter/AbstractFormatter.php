<?php

namespace AppBundle\Service\ActivityLog\EntityFormatter;

use Doctrine\ORM\EntityManagerInterface;


/**
 * Class AbstractFormatter
 * @package AppBundle\Service\ActivityLog\EntityFormatter
 */
abstract class AbstractFormatter
{

    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @param string $field
     * @param mixed $value
     * @param string $format
     * @return string|bool|int
     */
    public function normalizeValue($field, $value, $format = 'Y m d H:i:s')
    {
        if (method_exists($this, $field)) {
            return $this->$field($value);
        }

        if (is_array($value)) {
            $value = $this->toComment($value);
        }

        if ($value instanceof \DateTime) {
            $value = $value->format($format);
        }

        if (is_bool($value)) {
            $value = $value ? 'Yes' : 'No';
        }

        if (is_numeric($value)) {
            if (is_float($value) && $value < 1 && $value != 0) {
                $value = number_format($value * 100, 2) . '%';
            } else {
                $value = number_format($value, 2);
            }
        }

        return $value;
    }

    /**
     * Convert assoc array to comment style
     *
     * @param array $data
     * @return string
     */
    public function toComment(array $data)
    {
        $result = ['<ul>'];
        foreach ($data as $key => $value) {
            $result[] = '<li>' . $value . '</li>';
        }
        $result[] =  '</ul>';
        return implode(PHP_EOL, $result);
    }

    public function setEntityManager(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function fromCamelCase($camelCaseString) {
        $re = '/(?<=[a-z])(?=[A-Z])/x';
        $a = preg_split($re, $camelCaseString);
        return ucwords(join($a, " " ));
    }
}
