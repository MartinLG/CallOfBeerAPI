<?php

namespace CallOfBeer\ApiBundle\Document;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;

use FOS\ElasticaBundle\Configuration\Search;

/** 
 * @ExclusionPolicy("all")
 * @Search(repositoryClass="CallOfBeer\ApiBundle\SearchRepository\EventRepository")
 */
class CobEvent {
    
    /**
     * @Type("string")
     * @Expose
     */
    protected $id;

    /**
     * @Type("string")
     * @Expose
     */
    protected $name;

    /**
     * @Type("CallOfBeer\ApiBundle\Document\Geolocation")
     * @Expose
     */
    protected $location;

    /**
     * @Type("DateTime")
     * @Expose
     */
    protected $date;
 
    /**
     * @Type("CallOfBeer\ApiBundle\Document\Adress")
     * @Expose
     */
    protected $adress;

    public function __construct()
    {
        $this->setId();
        $this->setDate(new \DateTime);
    }

    /**
     * Get the Event's id
     *
     * @return String
     * @VirtualProperty 
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Set id
     *
     * @param  string $id
     * @return self
     */
    private function setId(){
        $salt = rand(1000000, 9999999);
        $this->id = md5(microtime() . $salt);
    }

    /**
     * Get the Event's name
     *
     * @return String
     * @VirtualProperty 
     */
    public function getName(){
        return $this->name;
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return self
     */
    public function setName($name){
        $this->name = $name;
        return $this;
    }

    /**
     * Get the Event's date
     *
     * @return \DateTime
     * @VirtualProperty 
     */
    public function getDate(){
        return $this->date;
    }

    /**
     * Set date
     *
     * @param  \DateTime $date
     * @return self
     */
    public function setDate($date){
        $this->date = $date;
        return $this;
    }

    /**
     * Get the Event's location
     *
     * @return CallOfBeer\ApiBundle\Document\Geolocation
     * @VirtualProperty 
     */
    public function getLocation(){
        return $this->location;
    }

    /**
     * Set location
     *
     * @param  CallOfBeer\ApiBundle\Document\Geolocation $location
     * @return self
     */
    public function setLocation($location){
        $this->location = $location;
        return $this;
    }

    /**
     * Get the Event's adress
     *
     * @return CallOfBeer\ApiBundle\Document\Adress
     * @VirtualProperty 
     */
    public function getAdress(){
        return $this->adress;
    }

    /**
     * Set adress
     *
     * @param  CallOfBeer\ApiBundle\Document\Adress $adress
     * @return self
     */
    public function setAdress($adress){
        $this->adress = $adress;
        return $this;
    }
}   