<?php

namespace AppBundle\Entity\Interfaces;

/**
 * Interface which define that entity has a parent
 *
 * Interface LoggableChildInterface
 * @package AppBundle\Entity\Interfaces
 */
interface LoggableChildInterface
{
    /**
     * @return object
     */
    public function getParentEntity();
}
