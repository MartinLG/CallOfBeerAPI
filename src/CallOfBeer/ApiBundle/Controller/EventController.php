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

use Symfony\Component\Serializer\Exception\InvalidArgumentException;

class EventController extends Controller
{
    public function getEventsAction()
    {
        $request = $this->getRequest();
        $topLat = $request->query->get('topLat');
        $topLon = $request->query->get('topLon');
        $botLat = $request->query->get('botLat');
        $botLon = $request->query->get('botLon');

        if (in_array(null, array($topLon, $topLat, $botLon, $botLat))) {
            throw new InvalidArgumentException("Bad parameters. Paramaters : topLat, topLon, botLat, botLon");
        }

        $finder = $this->container->get('fos_elastica.finder.callofbeer.event');

        $dateLimit = new \DateTime();
        $dateLimit->sub(new \DateInterval('PT6H'));

        $dateFilter = new \Elastica\Filter\Range(
            'date', 
            array(
                'gte' => $dateLimit->format("Y-m-d\TH:i:sO")
            )
        );

        $geoFilter = new \Elastica\Filter\GeoBoundingBox('geolocation', array(
            array('lon' => floatval($topLon), 'lat' => floatval($topLat)),
            array('lon' => floatval($botLon), 'lat' => floatval($botLat))
            )
        );

        $nested = new \Elastica\Filter\Nested();
        $nested->setFilter($geoFilter);
        $nested->setPath("address");

        $boolFilter = new \Elastica\Filter\Bool();
        $boolFilter->addMust($nested);
        $boolFilter->addMust($dateFilter);

        $query = new Filtered(new MatchAll(), $boolFilter);

        $elasticaQuery = new \Elastica\Query();
        $elasticaQuery->setQuery($query);

        $events = $finder->find($elasticaQuery);

        return $events;
    }

    public function postEventsAction()
    {
        $request        = $this->getRequest();
        $eventName      = $request->request->get('eventName');
        $eventDate      = $request->request->get('eventDate');
        $addressName    = $request->request->get('addressName');
        $addressAddress = $request->request->get('addressAddress');
        $addressZip     = $request->request->get('addressZip');
        $addressCity    = $request->request->get('addressCity');
        $addressCountry = $request->request->get('addressCountry');
        $addressLat     = $request->request->get('addressLat');
        $addressLon     = $request->request->get('addressLon');

        if (in_array(null, array($eventName, $eventDate, $addressLat, $addressLon))) {
            throw new InvalidArgumentException("Bad parameters. Paramaters : eventName, eventDate, addressLon, addressLat. Options : addressName, addressAddress, addressZip, addressCity, addressCountry");
        }

        $em = $this->getDoctrine()->getManager();

        $event = new CobEvent();
        $date = new \DateTime();
        $date->setTimestamp(intval($eventDate));
        $event->setDate($date);
        $event->setName($eventName);

        $address = new Address();
        $address->setName($addressName);
        $address->setAddress($addressAddress);
        $address->setZip($addressZip);
        $address->setCity($addressCity);
        $address->setCountry($addressCountry);

        $geoloc = array($addressLon, $addressLat);

        $address->setGeolocation($geoloc);
        $event->setAddress($address);

        $em->persist($event);
        $em->flush();

        return true;
    }
}