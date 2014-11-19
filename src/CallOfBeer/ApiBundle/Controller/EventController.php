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
        $events = array();

        $event = new CobEvent();
        $adress = new Adress();
        $location = new Geolocation();

        $location->setLat(44.8344);
        $location->setLon(-0.5754);

        $adress->setName("Ingesup");
        $adress->setAdress("89 quai des Chartrons");
        $adress->setZip(33000);
        $adress->setCity("Bordeaux");
        $adress->setCountry("France");

        $event->setName("Great Party at School");
        $event->setAdress($adress);
        $event->setLocation($location);

        $event2 = new CobEvent();
        $adress2 = new Adress();
        $location2 = new Geolocation();

        $location2->setLat(44.84);
        $location2->setLon(-0.58);

        $adress2->setName("Martin's House");
        $adress2->setAdress("1 chemin de Pauge");
        $adress2->setZip(33140);
        $adress2->setCity("Villenave d'Ornon");
        $adress2->setCountry("France");

        $event2->setName("Great Party at Martin's House");
        $event2->setAdress($adress2);
        $event2->setLocation($location2);

        array_push($events, $event);
        array_push($events, $event2);

        return $events;
    }
}