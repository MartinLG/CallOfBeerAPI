<?php

namespace CallOfBeer\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use CallOfBeer\ApiBundle\Entity\Address;

use Symfony\Component\HttpFoundation\JsonResponse;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class GeocodingController extends Controller
{
    /**
     * API endpoint to get an Address by Geolocation
     *
     * @ApiDoc(
     *  resource=true,
     *  requirements={
     *      {"name"="lat", "requirement"="\d+", "require"=true, "dataType"="integer"},
     *      {"name"="lon", "requirement"="\d+", "require"=true, "dataType"="integer"}
     *  }
     * )
     */
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

        $addresses = $this->container
                    ->get('bazinga_geocoder.geocoder')
                    ->using('openstreetmap')
                    ->reverse($lat, $lon);

        $addressReturn = $addresses->first();

        $address = new Address();

        $geo = array();
        array_push($geo, $lat);
        array_push($geo, $lon);
        $address->setGeolocation($geo);

        if ($addressReturn->getPostalCode()) {
            $address->setZip($addressReturn->getPostalCode());
        }

        $textAdress = "";
        if ($addressReturn->getStreetNumber()) {
            $textAdress = $addressReturn->getStreetNumber() . " ";
        }

        if ($addressReturn->getStreetName()) {
            $textAdress = $textAdress . $addressReturn->getStreetName();
        }
        $address->setAddress($textAdress);

        if ($addressReturn->getLocality()) {
            $address->setCity($addressReturn->getLocality());
        }

        if ($addressReturn->getCountry()) {
            $address->setCountry($addressReturn->getCountry());
        }

        $serializer = $this->container->get('jms_serializer');
        
        $response = new Response($serializer->serialize($address, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * API endpoint to get a Geolocation by Address
     *
     * @ApiDoc(
     *  resource=true,
     *  requirements={
     *      {"name"="address", "requirement"="\s+", "require"=true, "dataType"="string"}
     *  }
     * )
     */
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