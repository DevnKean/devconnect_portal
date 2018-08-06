<?php
namespace AppBundle\Service;

use \GuzzleHttp\Client;

class GravityClient
{

    private $client;

    private $host;

    private $publicKey;

    private $privateKey;

    private $expiresSeconds = null;

    const BASE_URL = 'gravityformsapi/';

    public function __construct($host, $publicKey, $privateKey)
    {
        $this->host = $host;
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $this->client = new Client();
    }


    protected function get($route)
    {
        $url = $this->generateUrl('GET', $route);
        $response = $this->client->request('GET', $url);

        if ($response->getStatusCode() == 200) {
            $content = json_decode($response->getBody()->getContents(), true);
            return $content['response'];
        }

        return $response->getBody()->getContents();
    }

    protected function calculateSignature($method, $route, $expires)
    {
        $stringToSign = sprintf("%s:%s:%s:%s", $this->publicKey, $method, $route, $expires);
        $hash = hash_hmac("sha1", $stringToSign, $this->privateKey, true);
        $signature = rawurlencode(base64_encode($hash));
        return $signature;
    }

    protected function generateUrl($method, $route, $query = null)
    {
        //expiration based from the current time
        $expires = !empty($this->expiresSeconds) && is_numeric($this->expiresSeconds) ? time() + $this->expiresSeconds : time() + 600;

        $signature = $this->calculateSignature($method, $route, $expires);

        if (!empty($query)) {
            $query = [];
        }

        $query["api_key"] = $this->publicKey;
        $query["signature"] = $signature;
        $query["expires"] = $expires;

        $queryString = http_build_query($query);
        $queryString = urldecode($queryString);

        return $this->host . static::BASE_URL . $route . "?" . $queryString;
    }

    public function getForm($formId)
    {
        $route = 'forms/' . $formId;

        return $this->get($route);
    }

    public function getEntry($entryId)
    {
        $route = 'entries/' . $entryId;

        return $this->get($route);
    }
}