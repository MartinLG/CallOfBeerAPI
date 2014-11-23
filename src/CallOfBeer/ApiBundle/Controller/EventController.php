<?php

namespace CallOfBeer\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CallOfBeer\ApiBundle\Entity\Address;
use CallOfBeer\ApiBundle\Entity\CobEvent;
use CallOfBeer\ApiBundle\Entity\Geolocation;

use Elastica\Filter\GeoDistance;
use Elastica\Query;
use Elastica\Query\MatchAll;
use Elastica\Query\Filtered;

class EventController extends Controller
{
    public function getEventsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $finder = $this->container->get('fos_elastica.index.callofbeer.event');

        $geoFilter = new GeoDistance('geolocation', array('lon' => -0.58,
             'lat' => 44.85), '100km');

        $nested  = new \Elastica\Filter\Nested();
        $nested->setFilter($geoFilter);
        $nested->setPath("address");

        $query = new Filtered(new MatchAll(), $nested);

        $elasticaQuery        = new \Elastica\Query();
        $elasticaQuery->setQuery($query);

        $events = $finder->search($elasticaQuery);

        return $events;
    }

    public function postEventsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $event = new CobEvent();
        $event->setDate(new \DateTime());
        $event->setName("Martin");

        $address = new Address();
        $address->setName("TEUCvgfZ");
        $address->setAddress("56 qubgbdfbai des Chartrons");
        $address->setZip(33000);
        $address->setCity("Bordeaubhx");
        $address->setCountry("Francbghde");

        $geoloc = array(-0.667, 44.868);

        $address->setGeolocation($geoloc);
        $event->setAddress($address);

        $em->persist($event);
        $em->flush();

        return true;
    }
}