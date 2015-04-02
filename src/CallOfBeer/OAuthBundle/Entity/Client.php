<?php

namespace CallOfBeer\OAuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * Client
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CallOfBeer\OAuthBundle\Entity\ClientRepository")
 *
 * @ExclusionPolicy("all")
 */
class Client extends BaseClient
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
     * @var string
     * @Type("string")
     * @Expose
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;


    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Address
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @VirtualProperty
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
}
