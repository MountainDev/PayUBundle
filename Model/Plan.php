<?php

namespace RadnoK\PayUBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Table(name="payu_plan")
 * @ORM\Entity(repositoryClass="RadnoK\PayUBundle\Repository\PlanRepository")
 */
abstract class Plan implements PlanInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
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
     * @ORM\Column(name="price_description", type="text", nullable=true)
     */
    protected $priceDescription = null;

    /**
     * @ORM\Column(name="active", type="boolean")
     */
    protected $active = true;

    /**
     * @ORM\Column(name="type", type="PlanType", nullable=false)
     * @DoctrineAssert\Enum(entity="RadnoK\PayUBundle\DBAL\Types\PlanType")
     */
    protected $type;

    /**
     * @ORM\OneToMany(targetEntity="RadnoK\PayUBundle\Entity\Subscription", mappedBy="plan")
     */
    protected $subscriptions;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Company", mappedBy="choice")
     */
    protected $companies;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPriceDescription(): ?string
    {
        return $this->priceDescription;
    }

    public function setPriceDescription(string $priceDescription): self
    {
        $this->priceDescription = $priceDescription;

        return $this;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active)
    {
        $this->active = $active;

        return $this;
    }

    public function getType(): string
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

    public function getCompanies()
    {
        return $this->companies;
    }

    public function setCompanies($companies)
    {
        $this->companies = $companies;

        return $this;
    }
}
