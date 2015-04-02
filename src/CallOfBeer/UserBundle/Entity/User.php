<?php

namespace CallOfBeer\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use CallOfBeer\OAuthBundle\Entity\Client;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="cob_user")
 * @ORM\Entity(repositoryClass="CallOfBeer\UserBundle\Entity\UserRepository")
 *
 * @ExclusionPolicy("all")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Expose
     */
    protected $id;

    /**
     * @Type("CallOfBeer\OAuthBundle\Entity\Client")
     * @Expose
     * @ORM\ManyToMany(targetEntity="CallOfBeer\OAuthBundle\Entity\Client", cascade={"persist"})
     */
    private $authorizedClients;

    public function __construct()
    {
        parent::__construct();
        $this->authorizedClients = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @VirtualProperty 
     * @return integer 
     */
    public function getId() 
    {
        return $this->id;
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

    /**
     * Get AuthorizedeClients
     *
     * @VirtualProperty
     * @return \CallOfBeer\OAuthBundle\Entity\Client  
     */
    public function getAuthorizedClients()
    {
       return $this->authorizedClients;
    }
}
