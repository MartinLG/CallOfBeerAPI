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
     * @Type("CallOfBeer\UserBundle\Entity\User")
     * @Expose
     * @ORM\ManyToMany(targetEntity="CallOfBeer\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinTable(name="cobevent_users_subscriber",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $subscribers;

    /**
     * @Type("CallOfBeer\UserBundle\Entity\User")
     * @Expose
     * @ORM\ManyToMany(targetEntity="CallOfBeer\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinTable(name="cobevent_users_guest",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $guests;

    /**
     * @Type("CallOfBeer\UserBundle\Entity\User")
     * @Expose
     * @ORM\ManyToMany(targetEntity="CallOfBeer\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinTable(name="cobevent_users_declined",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $declined;

    /**
     * @Type("CallOfBeer\UserBundle\Entity\User")
     * @Expose
     * @ORM\ManyToMany(targetEntity="CallOfBeer\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinTable(name="cobevent_users_maybe",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $maybe;


    public function __construct()
    {
        $this->subscribers = new ArrayCollection();
        $this->guests = new ArrayCollection();
        $this->maybe = new ArrayCollection();
        $this->declined = new ArrayCollection();
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

    public function addSubscriber(User $user)
    {
        $this->subscribers[] = $user;
        return $this;
    }

    public function isSubscriber(User $user)
    {
        return $this->subscribers->contains($user);
    }

    public function removeSubscriber(User $user)
    {
        $this->subscribers->removeElement($user);
    }

    /**
     * Get Subscribers
     *
     * @VirtualProperty
     * @return \CallOfBeer\UserBundle\Entity\User  
     */
    public function getSubscribers()
    {
       return $this->subscribers;
    }

    public function addGuest(User $user)
    {
        $this->guests[] = $user;
        return $this;
    }

    public function isGuest(User $user)
    {
        return $this->guests->contains($user);
    }

    public function removeGuest(User $user)
    {
        $this->guests->removeElement($user);
    }

    /**
     * Get Guests
     *
     * @VirtualProperty
     * @return \CallOfBeer\UserBundle\Entity\User  
     */
    public function getGuests()
    {
       return $this->guests;
    }

    public function addDeclined(User $user)
    {
        $this->declined[] = $user;
        return $this;
    }

    public function isDeclined(User $user)
    {
        return $this->declined->contains($user);
    }

    public function removeDeclined(User $user)
    {
        $this->declined->removeElement($user);
    }

    /**
     * Get Declined
     *
     * @VirtualProperty
     * @return \CallOfBeer\UserBundle\Entity\User  
     */
    public function getDeclined()
    {
       return $this->declined;
    }

    public function addMaybe(User $user)
    {
        $this->maybe[] = $user;
        return $this;
    }

    public function isMaybe(User $user)
    {
        return $this->maybe->contains($user);
    }

    public function removeMaybe(User $user)
    {
        $this->maybe->removeElement($user);
    }

    /**
     * Get Maybe
     *
     * @VirtualProperty
     * @return \CallOfBeer\UserBundle\Entity\User  
     */
    public function getMaybe()
    {
       return $this->maybe;
    }
}
