<?php
/**
 * ShowUserEvent.php
 * avanzu-admin
 * Date: 23.02.14
 */

namespace ThemeBundle\Event;


use AppBundle\Entity\User;
use ThemeBundle\Model\UserInterface;

class ShowUserEvent extends  ThemeEvent {

    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return User | UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }


}