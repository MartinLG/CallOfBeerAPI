<?php

namespace CallOfBeer\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;

use CallOfBeer\UserBundle\Entity\User;

use FOS\ElasticaBundle\Configuration\Search;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * CobEvent
 *
 * @ExclusionPolicy("all")
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CallOfBeer\ApiBundle\Entity\CobEventRepository")
 */
class CobEvent
{
    /**
     * @var integer
     *
     * @Type("integer")
     * @Expose
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Type("string")
     * @Expose
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var boolean
     *
     * @Type("boolean")
     * @Expose
     * @ORM\Column(name="private", type="boolean")
     */
    private $private;

    /**
     * @var \DateTime
     *
     * @Type("DateTime")
     * @Expose
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @Type("CallOfBeer\ApiBundle\Entity\Address")
     * @Expose
     * @ORM\ManyToOne(targetEntity="Address", inversedBy="events", cascade={"persist"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     */
    protected $address;

    /**
     * @Type("CallOfBeer\ApiBundle\Entity\EventUserRole")
     * 
     * @ORM\OneToMany(targetEntity="EventUserRole", mappedBy="event", cascade={"remove", "persist"})
     */
    protected $users;

    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Set name
     *
     * @param string $name
     * @return CobEvent
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

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return CobEvent
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @VirtualProperty
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set address
     *
     * @param \CallOfBeer\ApiBundle\Entity\Address $address
     * @return CobEvent
     */
    public function setAddress(\CallOfBeer\ApiBundle\Entity\Address $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @VirtualProperty
     * @return \CallOfBeer\ApiBundle\Entity\Address 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set private
     *
     * @param boolean $private
     * @return CobEvent
     */
    public function setPrivate($private)
    {
        $this->private = $private;

        return $this;
    }

    /**
     * Get private
     *
     * @VirtualProperty
     * @return \boolean 
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * Add users
     *
     * @param \CallOfBeer\ApiBundle\Entity\EventUserRole $users
     * @return EventUserRole
     */
    public function addUser(\CallOfBeer\ApiBundle\Entity\EventUserRole $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \CallOfBeer\ApiBundle\Entity\EventUserRole $users
     */
    public function removeUser(\CallOfBeer\ApiBundle\Entity\EventUserRole $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}
