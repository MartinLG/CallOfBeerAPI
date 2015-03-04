<?php

namespace CallOfBeer\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;


class UserCreatedListener implements EventSubscriberInterface
{
    private $em;
    private $security;
    private $request;

    public function __construct($em)
    {
        $this->em = $em; 
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => 'onUserCreatedSuccess',
        );
    }

    public function onUserCreatedSuccess($event)
    {
        $user = $this->em->getRepository('CallOfBeerUserBundle:User')->findOneById($event->getUser()->getId());

        $cob = $this->em->getRepository('CallOfBeerOAuthBundle:Client')->findOneByName('TestMobile');

        $user->addAuthorizedClient($cob);

        $this->em->persist($user);
        $this->em->flush();
    }
}