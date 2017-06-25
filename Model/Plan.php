<?php

namespace RadnoK\PayUBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="payu_plan")
 */
abstract class Plan implements PlanInterface
{
    protected $id;

    /**
     * @ORM\Column(name="code", type="string", length=255)
     */
    protected $code;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\Column(name="price", type="float")
     */
    protected $price;

    /**
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     * @ORM\Column(name="active", type="boolean")
     */
    protected $active = true;

    /**
     * @ORM\Column(name="type", type="PlanTypeType", nullable=false)
     * @DoctrineAssert\Enum(entity="RadnoK\PayUBundle\DBAL\Types\PlanTypeType")
     */
    protected $type;

    /**
     * @ORM\OneToMany(targetEntity="RadnoK\PayUBundle\Model\SubscriptionInterface", mappedBy="plan",cascade={"remove"}, orphanRemoval=true)
     */
    protected $subscriptions;

    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active)
    {
        $this->active = $active;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    public function setSubscriptions($subscriptions)
    {
        $this->subscriptions = $subscriptions;

        return $this;
    }
}
