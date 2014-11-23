<?php

namespace CallOfBeer\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CallOfBeer\ApiBundle\Entity\Address;
use CallOfBeer\ApiBundle\Entity\CobEvent;
use CallOfBeer\ApiBundle\Entity\Geolocation;

class EventController extends Controller
{
    public function getEventsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository('CallOfBeerApiBundle:CobEvent')->findAll();

        // $article_type = $this->get('fos_elastica.index.callofbeer.event');
        // $test = $article_type->search("Old");

        return $events;
    }

    public function postEventsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $event = new CobEvent();
        $event->setDate(new \DateTime());
        $event->setName("Old Party");

        $address = new Address();
        $address->setName("TEUCZ");
        $address->setAddress("56 quai des Chartrons");
        $address->setZip(33000);
        $address->setCity("Bordeaux");
        $address->setCountry("France");

        $geoloc = array(44.8578, -0.5867);

        $address->setGeolocation($geoloc);
        $event->setAddress($address);

        $em->persist($event);
        $em->flush();

        return true;
    }
}