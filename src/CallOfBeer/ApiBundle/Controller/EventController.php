<?php

namespace CallOfBeer\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CallOfBeer\ApiBundle\Document\CobEvent;
use CallOfBeer\ApiBundle\Document\Adress;
use CallOfBeer\ApiBundle\Document\Geolocation;

class EventController extends Controller
{
    public function getEventsAction()
    {
        $event = new CobEvent();
        $adress = new Adress();
        $location = new Geolocation();

        $location->setLong(44.92);
        $location->setLat(-0.75);

        $adress->setName("Ingesup");
        $adress->setAdress("89 quai des Chartrons");
        $adress->setZip(33000);
        $adress->setCity("Bordeaux");
        $adress->setCountry("France");

        $event->setName("Great Party at School");
        $event->setAdress($adress);
        $event->setLocation($location);

        // var_dump($event);die();

        return $event;
    }
}