<?php

namespace CallOfBeer\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;

use FOS\ElasticaBundle\Configuration\Search;

/**
 * CobEvent
 *
 * @ExclusionPolicy("all")
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CallOfBeer\ApiBundle\Entity\CobEventRepository")
 * @Search(repositoryClass="CallOfBeer\ApiBundle\SearchRepository\EventRepository")
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
}
