<?php

namespace CallOfBeer\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use CallOfBeer\ApiBundle\Entity\Address;
use CallOfBeer\ApiBundle\Entity\CobEvent;
use CallOfBeer\ApiBundle\Entity\EventUserRole;

use Elastica\Filter\GeoDistance;
use Elastica\Query;
use Elastica\Query\MatchAll;
use Elastica\Query\Filtered;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class EventController extends Controller
{
    /**
     * API endpoint to get a specific Event by Id
     *
     * @ApiDoc(
     *  resource=true,
     *  description="API endpoint to get a specific Event by Id",
     *  requirements={
     *      {"name"="id", "requirement"="\d+", "require"=true, "dataType"="integer"}
     *  }
     * )
     */
    public function getEventAction()
    {
        $request = $this->getRequest();
        $id = $request->query->get('id');
        
        if (is_null($id)) {
            $response = new Response();
            $response->setStatusCode(400);
            $response->setContent("Bad parameters. Paramaters : id.");
            return $response;
        }

        $em = $this->get('doctrine')->getEntityManager();
        $event = $em->getRepository('CallOfBeerApiBundle:CobEvent')->find(intval($id));

        if (is_null($event)) {
            $response = new Response();
            $response->setStatusCode(404);
            $response->setContent("No event found.");
            return $response;
        }

        return $event;
    }

    /**
     * API endpoint to get Events by Geolocation
     *
     * @ApiDoc(
     *  resource=true,
     *  requirements={
     *      {"name"="topLat", "requirement"="\d+", "require"=true, "dataType"="integer"},
     *      {"name"="botLat", "requirement"="\d+", "require"=true, "dataType"="integer"},
     *      {"name"="topLon", "requirement"="\d+", "require"=true, "dataType"="integer"},
     *      {"name"="botLon", "requirement"="\d+", "require"=true, "dataType"="integer"}
     *  }
     * )
     */
    public function getEventsAction()
    {

        $request = $this->getRequest();
        $topLat = $request->query->get('topLat');
        $topLon = $request->query->get('topLon');
        $botLat = $request->query->get('botLat');
        $botLon = $request->query->get('botLon');

        if (is_null($topLon) || is_null($topLat) || is_null($botLon) || is_null($botLat)) {
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

        $events = $finder->find($elasticaQuery, 100);

        return $events;
    }

    /**
     * API endpoint to post or update (if eventId is set) an Event
     *
     * @ApiDoc(
     *  resource=true,
     *  filters={
     *      {"name"="eventId", "dataType"="integer", "description"="Edit an Event if set"}
     *  },
     *  requirements={
     *      {"name"="eventName", "require"=true, "requirement"="\s+", "dataType"="string"},
     *      {"name"="eventDate", "require"=true, "requirement"="\d+", "dataType"="integer"},
     *      {"name"="addressLon", "require"=true, "requirement"="\d+", "dataType"="integer"},
     *      {"name"="addressLat", "require"=true, "requirement"="\d+", "dataType"="integer"}
     *  },
     *  parameters={
     *      {"name"="addressName", "required"=false, "dataType"="string"},
     *      {"name"="addressAddress", "required"=false, "dataType"="string"},
     *      {"name"="addressZip", "required"=false, "requirement"="\d+", "dataType"="integer", "Code Postal bande d'ignares !"},
     *      {"name"="addressCity", "required"=false, "dataType"="string"},
     *      {"name"="addressCountry", "required"=false, "dataType"="string"},
     *      {"name"="private", "required"=false, "dataType"="boolean"}
     *  }
     * )
     */
    public function postEventsAction()
    {

        if (false === $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

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
        $private        = $request->request->get('private');

        if ($eventId == null && (is_null($eventName) || is_null($eventDate) || is_null($addressLat) || is_null($addressLon))) {
            $response = new Response();
            $response->setStatusCode(400);
            $response->setContent("Bad parameters. Paramaters : eventName, eventDate, addressLon, addressLat. To Update : eventId. Options : addressName, addressAddress, addressZip, addressCity, addressCountry, private.");
            return $response;
        }

        $em = $this->getDoctrine()->getManager();

        if ($eventId == null) {
            $event = new CobEvent();
            $address = new Address();
        } else {
            $event = $em->getRepository('CallOfBeerApiBundle:CobEvent')->find(intval($eventId));
            if ($event == null) {
                $response = new Response();
                $response->setStatusCode(400);
                $response->setContent("Bad parameters. Paramaters : eventId does not match any events.");
                return $response;
            }
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
            $address->setLat(floatval($addressLat));
            $address->setLon(floatval($addressLon));
            $geoloc = array(floatval($addressLon), floatval($addressLat));
            $address->setGeolocation($geoloc);
        }
        $event->setAddress($address);

        if ($private != null) {
            $event->setPrivate($private);
        } else {
            $event->setPrivate(false);
        }

        $user = $this->getUser();

        $admin = new EventUserRole();
        $admin->setRole("subscriber");
        $admin->setAdmin(true);
        $admin->setUser($user);

        $user->addEvent($admin);

        $event->addUser($admin);

        $em->persist($event);
        $em->persist($admin);
        $em->persist($user);
        $em->flush();

        return $event;
    }

    /**
     * API endpoint to post User's role in an event
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="eventId", "require"=true, "requirement"="\d+", "dataType"="integer"},
     *      {"name"="userId", "require"=true, "requirement"="\d+", "dataType"="integer"},
     *      {"name"="role", "require"=true, "requirement"="\s", "dataType"="string"}
     *  }
     * )
     */
    public function postEventUserRoleAction()
    {

        if (false === $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        $request        = $this->getRequest();
        $eventId        = $request->request->get('eventId');
        $userId         = $request->request->get('userId');
        $role           = $request->request->get('role');

        if ($eventId == null || $role == null || !in_array($role, array('subscriber', 'guest', 'admin', 'maybe', 'declined'))) {
            $response = new Response();
            $response->setStatusCode(400);
            $response->setContent("Bad parameters. Paramaters : eventId role. Option : userId (if different from logged user).");
            return $response;
        }

        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('CallOfBeerApiBundle:CobEvent')->find(intval($eventId));

        if ($event == null) {
            $response = new Response();
            $response->setStatusCode(400);
            $response->setContent("Bad parameters. Paramaters : eventId does not match any event.");
            return $response;
        }

        $user = $this->getUser();

        if ($userId == null) {
            $userToAdd = $user;
        } else {
            $userToAdd = $em->getRepository('CallOfBeerUserBundle:User')->find(intval($userId));
        }

        if ($user == null) {
            $response = new Response();
            $response->setStatusCode(400);
            $response->setContent("Bad parameters. Paramaters : userId does not match any user.");
            return $response;
        }

        $userRole = $em->getRepository('CallOfBeerApiBundle:EventUserRole')->getByEventAndUser($event->getId(), $user->getId());

        if ($userRole == null && $event->getPrivate()) {
            $response = new Response();
            $response->setStatusCode(403);
            $response->setContent("You don't have rights to get this role on this event.");
            return $response;
        }

        $em->persist($event);
        $em->flush();

        return $event;
    }

    
}