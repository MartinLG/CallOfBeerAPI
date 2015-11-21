<?php

namespace CallOfBeer\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;

use CallOfBeer\UserBundle\Entity\User;
use CallOfBeer\ApiBundle\Entity\CobEvent;

use FOS\ElasticaBundle\Configuration\Search;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * EventUserRole
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CallOfBeer\ApiBundle\Entity\EventUserRoleRepository")
 * @UniqueEntity(fields={"event","user"})
 */
class EventUserRole
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Type("CallOfBeer\ApiBundle\Entity\CobEvent")
     * @Expose
     * @ORM\ManyToOne(targetEntity="CobEvent", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    protected $event;

    /**
     * @Type("CallOfBeer\UserBundle\Entity\User")
     * @Expose
     * @ORM\ManyToOne(targetEntity="CallOfBeer\UserBundle\Entity\User", inversedBy="events", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255)
     */
    private $role;

    /**
     * @var boolean
     *
     * @ORM\Column(name="admin", type="boolean")
     */
    private $admin;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return EventUserRole
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set admin
     *
     * @param boolean $admin
     *
     * @return EventUserRole
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return boolean
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set event
     *
     * @param \CallOfBeer\ApiBundle\Entity\CobEvent $event
     * @return CobEvent
     */
    public function setEvent(\CallOfBeer\ApiBundle\Entity\CobEvent $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @VirtualProperty
     * @return \CallOfBeer\ApiBundle\Entity\Event 
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set user
     *
     * @param \CallOfBeer\UserBundle\Entity\User $user
     * @return User
     */
    public function setUser(\CallOfBeer\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @VirtualProperty
     * @return \CallOfBeer\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}

