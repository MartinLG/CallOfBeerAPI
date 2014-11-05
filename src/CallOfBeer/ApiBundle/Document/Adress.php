<?php

namespace CallOfBeer\ApiBundle\Document;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;

/** 
 * @ExclusionPolicy("all") 
 */
class Adress {
    
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
     * @Type("string")
     * @Expose
     */
    protected $adress;

    /**
     * @Type("integer")
     * @Expose
     */
    protected $zip;
 
    /**
     * @Type("string")
     * @Expose
     */
    protected $city;

    /**
     * @Type("string")
     * @Expose
     */
    protected $country;

    public function __construct()
    {
        $this->setId();
    }

    /**
     * Get the Adress's id
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
     * Get the Adress's name
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
     * Get the Adress's adress
     *
     * @return String
     * @VirtualProperty 
     */
    public function getAdress(){
        return $this->adress;
    }

    /**
     * Set adress
     *
     * @param  string $adress
     * @return self
     */
    public function setAdress($adress){
        $this->adress = $adress;
        return $this;
    }

    /**
     * Get the Adress's city
     *
     * @return String
     * @VirtualProperty 
     */
    public function getCity(){
        return $this->city;
    }

    /**
     * Set city
     *
     * @param  string $city
     * @return self
     */
    public function setCity($city){
        $this->city = $city;
        return $this;
    }

    /**
     * Get the Adress's country
     *
     * @return String
     * @VirtualProperty 
     */
    public function getCountry(){
        return $this->country;
    }

    /**
     * Set country
     *
     * @param  string $country
     * @return self
     */
    public function setCountry($country){
        $this->country = $country;
        return $this;
    }

    /**
     * Get the Adress's zip
     *
     * @return integer
     * @VirtualProperty 
     */
    public function getZip(){
        return $this->zip;
    }

    /**
     * Set zip
     *
     * @param  integer $zip
     * @return self
     */
    public function setZip($zip){
        $this->zip = $zip;
        return $this;
    }

}   