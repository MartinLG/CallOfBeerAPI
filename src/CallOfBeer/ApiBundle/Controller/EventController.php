<?php

namespace CallOfBeer\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
            $response = new Response();
            $response->setStatusCode(400);
            $response->setContent("Bad parameters. Paramaters : topLat, topLon, botLat, botLon.");
            return $response;
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
        $eventId        = $request->request->get('eventId');
        $eventName      = $request->request->get('eventName');
        $eventDate      = $request->request->get('eventDate');
        $addressName    = $request->request->get('addressName');
        $addressAddress = $request->request->get('addressAddress');
        $addressZip     = $request->request->get('addressZip');
        $addressCity    = $request->request->get('addressCity');
        $addressCountry = $request->request->get('addressCountry');
        $addressLat     = $request->request->get('addressLat');
        $addressLon     = $request->request->get('addressLon');

        if ($eventId == null && in_array(null, array($eventName, $eventDate, $addressLat, $addressLon))) {
            $response = new Response();
            $response->setStatusCode(400);
            $response->setContent("Bad parameters. Paramaters : eventName, eventDate, addressLon, addressLat. To Update : eventId. Options : addressName, addressAddress, addressZip, addressCity, addressCountry.");
            return $response;
        }

        $em = $this->getDoctrine()->getManager();

        if ($eventId == null) {
            $event = new CobEvent();
            $address = new Address();
        } else {
            $event = $em->getRepository('CallOfBeerApiBundle:CobEvent')->find(intval($eventId));
            $address = $event->getAddress();
        }

        if ($eventDate != null) {
            $date = new \DateTime();
            $date->setTimestamp(intval($eventDate));
            $event->setDate($date);
        }
        if ($eventName != null) {
            $event->setName($eventName);
        }
        if ($addressName != null) {
            $address->setName($addressName);
        }
        if ($addressAddress != null) {
            $address->setAddress($addressAddress);
        }
        if ($addressZip != null) {
            $address->setZip($addressZip);
        }
        if ($addressCity != null) {
            $address->setCity($addressCity);
        }
        if ($addressCountry != null) {
            $address->setCountry($addressCountry);
        }
        if ($addressLon != null && $addressLat != null) {
            $geoloc = array(floatval($addressLon), floatval($addressLat));
            $address->setGeolocation($geoloc);
            $event->setAddress($address);
        }

        $em->persist($event);
        $em->flush();

        return true;
    }
}