<?php

namespace CallOfBeer\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use CallOfBeer\OAuthBundle\Entity\Client;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="cob_user")
 * @ORM\Entity(repositoryClass="CallOfBeer\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="CallOfBeer\OAuthBundle\Entity\Client", cascade={"persist"})
     */
    private $authorizedClients;

    public function __construct()
    {
        parent::__construct();
        $this->authorizedClients = new ArrayCollection();
    }

    public function addAuthorizedClient(Client $client)
    {
        $this->authorizedClients[] = $client;
        return $this;
    }

    public function isAuthorizedClient(Client $client)
    {
        return $this->authorizedClients->contains($client);
    }

    public function removeAuthorizedClient(Client $client)
    {
        $this->authorizedClients->removeElement($client);
    }

    public function getAuthorizedClients()
    {
       return $this->authorizedClients;
    }
}
