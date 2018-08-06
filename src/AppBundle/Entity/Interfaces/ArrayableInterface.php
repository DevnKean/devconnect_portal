<?php

namespace AppBundle\Entity\Interfaces;

/**
 * Interface ArrayableInterface
 * @package AppBundle\Entity\Interfaces
 */
interface ArrayableInterface
{
    /**
     * Get object instance as an array.
     * @return array
     */
    public function toArray();
}
