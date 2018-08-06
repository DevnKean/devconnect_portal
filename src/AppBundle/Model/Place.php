<?php

namespace AppBundle\Model;

/**
 * Represents (pieces of) a PlaceResult
 *
 * @see https://developers.google.com/maps/documentation/javascript/3.exp/reference#PlaceResult
 */
class Place
{

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $streetNumber;
    /**
     * @var string
     */
    private $route;
    /**
     * @var string
     */
    private $locality;

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $postalCode;

    /**
     * @var string
     */
    private $country;

    /**
     * Place constructor.
     *
     * @param string $name
     * @param null|string $streetNumber
     * @param null|string $route
     * @param null|string $locality
     * @param null|string $postalCode
     * @param null|string $state
     * @param null|string $country
     */
    public function __construct(
        $name,
        $streetNumber = null,
        $route = null,
        $locality = null,
        $postalCode = null,
        $state = null,
        $country = null
    ) {
        $this->setName($name);
        $this->setStreetNumber($streetNumber);
        $this->setRoute($route);
        $this->setLocality($locality);
        $this->setPostalCode($postalCode);
        $this->setState($state);
        $this->setCountry($country);
    }

    /**
     * @param array $data
     *
     * @return Place
     */
    public static function build(array $data)
    {
        if (!isset($data['name'])) {
            throw new \LogicException('$data must at least contains a `name` field');
        }

        return new self(
            $data['name'],
            isset($data['street_number']) ? $data['street_number'] : null,
            isset($data['route']) ? $data['route'] : null,
            isset($data['locality']) ? $data['locality'] : null,
            isset($data['postal_code']) ? $data['postal_code'] : null,
            isset($data['administrative_area_level_1']) ? $data['administrative_area_level_1'] : null,
            isset($data['country']) ? $data['country'] : null
        );
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        if ($this->name) {
            return sprintf(
                '%s %s, %s, %s, %s, %s',
                $this->streetNumber,
                $this->route,
                $this->locality,
                $this->state,
                $this->postalCode,
                $this->country
            );
        }

        return null;
    }

    public function __toString()
    {
       return $this->name;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'street_number' => $this->getStreetNumber(),
            'route' => $this->getRoute(),
            'locality' => $this->getLocality(),
            'postal_code' => $this->getPostalCode(),
            'administrative_area_level_1' => $this->getState(),
            'country' => $this->getCountry(),
        ];
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Place
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get streetNumber
     *
     * @return string
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    /**
     * Set streetNumber
     *
     * @param string $streetNumber
     *
     * @return Place
     */
    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    /**
     * Get route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set route
     *
     * @param string $route
     *
     * @return Place
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get locality
     *
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * Set locality
     *
     * @param string $locality
     *
     * @return Place
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return Place
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Place
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }
}