<?php

namespace CallOfBeer\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use CallOfBeer\ApiBundle\Entity\Address;

use Symfony\Component\HttpFoundation\JsonResponse;

class GeocodingController extends Controller
{
    public function getAddressAction()
    {
        $request = $this->getRequest();
        $lat = $request->query->get('lat');
        $lon = $request->query->get('lon');
        
        if (is_null($lat) || is_null($lon)) {
            $response = new Response();
            $response->setStatusCode(400);
            $response->setContent("Bad parameters. Paramaters : lat, lon.");
            return $response;
        }

        $addressReturn = $this->container
                    ->get('bazinga_geocoder.geocoder')
                    ->using('openstreetmap')
                    ->reverse($lat, $lon);

        $address = new Address();

        $geo = array();
        array_push($geo, $lat);
        array_push($geo, $lon);
        $address->setGeolocation($geo);

        if ($addressReturn->getZipcode()) {
            $address->setZip($addressReturn->getZipcode());
        }

        $textAdress = "";
        if ($addressReturn->getStreetNumber()) {
            $textAdress = $addressReturn->getStreetNumber() . " ";
        }

        if ($addressReturn->getStreetName()) {
            $textAdress = $textAdress . $addressReturn->getStreetName();
        }
        $address->setAddress($textAdress);

        if ($addressReturn->getCity()) {
            $address->setCity($addressReturn->getCity());
        }

        if ($addressReturn->getCountry()) {
            $address->setCountry($addressReturn->getCountry());
        }

        $serializer = $this->container->get('jms_serializer');
        
        $response = new Response($serializer->serialize($address, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function getGeolocAction()
    {
        $request = $this->getRequest();
        $address = $request->query->get('address');
        
        if (is_null($address)) {
            $response = new Response();
            $response->setStatusCode(400);
            $response->setContent("Bad parameters. Paramaters : address.");
            return $response;
        }

        $addressReturn = $this->container
                    ->get('bazinga_geocoder.geocoder')
                    ->using('openstreetmap')
                    ->geocode($address);

        $serializer = $this->container->get('jms_serializer');
        
        $response = new Response($serializer->serialize($addressReturn, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }
}