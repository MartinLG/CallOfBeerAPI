<?php

namespace CallOfBeer\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use CallOfBeer\UserBundle\Entity\User;
use CallOfBeer\ApiBundle\Entity\Address;
use CallOfBeer\ApiBundle\Entity\CobEvent;

use Elastica\Filter\GeoDistance;
use Elastica\Query;
use Elastica\Query\MatchAll;
use Elastica\Query\Filtered;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class UserController extends Controller
{
    /**
     * API endpoint to get a specific User by Id
     *
     * @ApiDoc(
     *  resource=true,
     *  description="API endpoint to get a specific User by Id",
     *  requirements={
     *      {"name"="id", "requirement"="\d+", "require"=true, "dataType"="integer"}
     *  }
     * )
     */
    public function getUserAction()
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
        $user = $em->getRepository('CallOfBeerUserBundle:User')->find(intval($id));

        if (is_null($user)) {
            $response = new Response();
            $response->setStatusCode(404);
            $response->setContent("No user found.");
            return $response;
        }

        return $user;
    }

    /**
     * API endpoint to register a User
     *
     * @ApiDoc(
     *  resource=true,
     *  requirements={
     *      {"name"="username", "require"=true, "requirement"="\s+", "dataType"="string"},
     *      {"name"="email", "require"=true, "requirement"="\d+", "dataType"="string"},
     *      {"name"="password", "require"=true, "requirement"="\d+", "dataType"="string"},
     *      {"name"="client_id", "require"=true, "requirement"="\d+", "dataType"="string"}
     *  }
     * )
     */
    public function postUsersAction()
    {
        $request    = $this->getRequest();
        $username   = $request->request->get('username');
        $email      = $request->request->get('email');
        $password   = $request->request->get('password');
        $client_id  = $request->request->get('client_id');

        if (is_null($username) || is_null($email) || is_null($password) || is_null($client_id)) {
            $response = new Response();
            $response->setStatusCode(400);
            $response->setContent("Bad parameters. Paramaters : username, email, password, client_id.");
            return $response;
        }

        $em = $this->getDoctrine()->getManager();

        $client = $em->getRepository('CallOfBeerOAuthBundle:Client')->find(intval($client_id));

        if (is_null($client)) {
            $response = new Response();
            $response->setStatusCode(403);
            return $response;
        }

        $userManager = $container->get('fos_user.user_manager');

        $user = $userManager->createUser();
        
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);

        $userManager->updateUser($user);

        return $user;
    }
}