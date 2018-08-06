<?php
/**
 * Created by PhpStorm.
 * User: Lixing
 * Date: 12/11/17
 * Time: 8:11 PM
 */
namespace AppBundle\Entity\Interfaces;

interface ActivityItem {
    public function getDate();

    public function getActivityType();

    public function getDetails();
}