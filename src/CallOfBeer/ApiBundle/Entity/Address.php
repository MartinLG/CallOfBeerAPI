<?php

namespace CallOfBeer\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * Address
 *
 * @ExclusionPolicy("all")
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CallOfBeer\ApiBundle\Entity\AddressRepository")
 */
class Address
{
    /**
     * @var integer
     *
     * @Type("integer")
     * @Expose
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Type("string")
     * @Expose
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @Type("string")
     * @Expose
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var integer
     *
     * @Type("integer")
     * @Expose
     * @ORM\Column(name="zip", type="integer", nullable=true)
     */
    private $zip;

    /**
     * @var string
     *
     * @Type("string")
     * @Expose
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @Type("string")
     * @Expose
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @Type("CallOfBeer\ApiBundle\Entity\CobEvent")
     * @Expose
     * @ORM\OneToMany(targetEntity="CobEvent", mappedBy="address", cascade={"remove", "persist"})
     */
    protected $events;

    /**
     * @Type("array")
     * @Expose
     * @ORM\Column(name="geolocation", type="array")
     */
    private $geolocation;

    /**
     * Get id
     *
     * @VirtualProperty 
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Address
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @VirtualProperty
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Address
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @VirtualProperty
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set zip
     *
     * @param integer $zip
     * @return Address
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @VirtualProperty
     * @return integer 
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Address
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @VirtualProperty
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Address
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @VirtualProperty
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add events
     *
     * @param \CallOfBeer\ApiBundle\Entity\CobEvent $events
     * @return Address
     */
    public function addEvent(\CallOfBeer\ApiBundle\Entity\CobEvent $events)
    {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \CallOfBeer\ApiBundle\Entity\CobEvent $events
     */
    public function removeEvent(\CallOfBeer\ApiBundle\Entity\CobEvent $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @VirtualProperty
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvents()
    {
        return $this->events;
    }


    /**
     * Set geolocation
     *
     * @param array $geolocation
     * @return Address
     */
    public function setGeolocation($geolocation)
    {
        $this->geolocation = $geolocation;

        return $this;
    }

    /**
     * Get geolocation
     *
     * @return array 
     */
    public function getGeolocation()
    {
        return $this->geolocation;
    }
}
