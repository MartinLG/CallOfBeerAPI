<?php

namespace CallOfBeer\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
       return $this->render('CallOfBeerApiBundle:Default:index.html.twig', array('name' => $name));
    }


    //getBeer
    public function GetBeer(){



    	return "ok";
    }

    //GetEvent

    //PostEvent
}
