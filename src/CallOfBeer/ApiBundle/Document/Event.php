<?php

namespace CallOfBeer\ApiBundle\Document;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;

/** 
 * @ExclusionPolicy("all") 
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
     * @Type("CallOfBeerApiBundle\Geolocation")
     * @Expose
     */
    protected $location;

    /**
     * @Type("DateTime")
     * @Expose
     */
    protected $date;
 
    /**
     * @Type("CallOfBeerApiBundle\Adress")
     * @Expose
     */
    protected $adress;

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
    public function setId($id){
        $this->id = $id;
        return $this;
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
}   