<?php

namespace CallOfBeer\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CallOfBeerUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
