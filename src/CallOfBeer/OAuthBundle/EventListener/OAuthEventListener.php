<?php

namespace CallOfBeer\OAuthBundle\EventListener;

use FOS\OAuthServerBundle\Event\OAuthEvent;

class OAuthEventListener
{
    private $em;
    private $security;
    private $request;

    public function __construct($em, $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function setRequest($request_stack)
    {
        $this->request = $request_stack->getCurrentRequest();
    }

    public function onPreAuthorizationProcess(OAuthEvent $event)
    {
        $event->setAuthorizedClient(true);
        if ($user = $this->getUser($event)) {
            $event->setAuthorizedClient(
                $user->isAuthorizedClient($event->getClient())
            );
        }
    }

    public function onPostAuthorizationProcess(OAuthEvent $event)
    {
        if ($event->isAuthorizedClient()) {
            if (null !== $client = $event->getClient()) {
                $user = $this->getUser($event);
                $user->addAuthorizedClient($client);
                $this->em->persist($user);
                $this->em->flush();
            }
        }
        // $this->security->setToken(null);
        // $this->request->getSession()->invalidate();
    }

    protected function getUser(OAuthEvent $event) {
        return $this->em->getRepository('CallOfBeerUserBundle:User')->findOneById($event->getUser()->getId());
    }
}