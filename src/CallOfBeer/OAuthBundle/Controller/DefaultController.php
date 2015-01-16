<?php

namespace CallOfBeer\OAuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CallOfBeerOAuthBundle:Default:index.html.twig', array('name' => $name));
    }
}
