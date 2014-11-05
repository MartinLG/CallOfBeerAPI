<?php

namespace CallOfBeer\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EventController extends Controller
{
    public function getEventsAction()
    {
        $data = array();
        $location = array();

        $location['lat'] = 44.8637279;
        $location['long'] = -0.586141;

        $data['name'] = "Awesome Party";
        $data['adress'] = "89 quai des Chartrons 33000 Bordeaux"
        $data['location'] = $location;
        // $data['date'] = new DateTime;

        return $data;
    }
}