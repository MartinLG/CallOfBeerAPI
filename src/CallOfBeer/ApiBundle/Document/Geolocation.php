<?php

namespace CallOfBeer\ApiBundle\Document;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;

/** 
 * @ExclusionPolicy("all") 
 */
class Geolocation {
    
    /**
     * @Type("double")
     * @Expose
     */
    protected $long;

    /**
     * @Type("double")
     * @Expose
     */
    protected $lat;

    /**
     * Get the Geolocation's long
     *
     * @return double
     * @VirtualProperty 
     */
    public function getLong(){
        return $this->long;
    }

    /**
     * Set long
     *
     * @param  double $long
     * @return self
     */
    public function setLong($long){
        $this->long = $long;
        return $this;
    }

    /**
     * Get the Geolocation's lat
     *
     * @return double
     * @VirtualProperty 
     */
    public function getLat(){
        return $this->lat;
    }

    /**
     * Set lat
     *
     * @param  double $lat
     * @return self
     */
    public function setLat($lat){
        $this->lat = $lat;
        return $this;
    }

}   