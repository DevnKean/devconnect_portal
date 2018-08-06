<?php
/**
 * Created by PhpStorm.
 * User: Lixing
 * Date: 30/11/17
 * Time: 10:37 PM
 */
namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class GravityClientFactory {

    /**
     * @param ContainerInterface $container
     * @param $source
     *
     * @return GravityClient
     */
    public static function createClient($container, $source)
    {
        if ($source == 'CX Central') {
            $client = new GravityClient(
                $container->getParameter('cxcentral.host'),
                $container->getParameter('cxcentral.public_key'),
                $container->getParameter('cxcentral.private_key'));
        } else {
            $client = new GravityClient(
                $container->getParameter('cxconnect.host'),
                $container->getParameter('cxconnect.public_key'),
                $container->getParameter('cxconnect.private_key'));
        }

        return $client;
    }
}