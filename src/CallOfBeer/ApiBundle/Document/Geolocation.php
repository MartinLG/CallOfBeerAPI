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
    protected $lon;

    /**
     * @Type("double")
     * @Expose
     */
    protected $lat;

    /**
     * Get the Geolocation's lon
     *
     * @return double
     * @VirtualProperty 
     */
    public function getLon(){
        return $this->lon;
    }

    /**
     * Set lon
     *
     * @param  double $lon
     * @return self
     */
    public function setLon($lon){
        $this->lon = $lon;
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