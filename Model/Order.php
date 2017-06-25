<?php

namespace RadnoK\PayUBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="payu_order")
 */
abstract class Order implements OrderInterface
{
    protected $id;

    /**
     * PayU order ID from API Response
     *
     * @ORM\Column(name="order_id", type="string", length=64, nullable=true)
     */
    protected $orderId;

    /**
     * Money amount
     *
     * @ORM\Column(name="amount", type="float")
     */
    protected $amount;

    /**
     * Payment description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(name="status", type="OrderStatusType", nullable=false)
     * @DoctrineAssert\Enum(entity="RadnoK\PayUBundle\DBAL\Types\OrderStatusType")
     */
    protected $status;

    /**
     * @ORM\Column(name="from_subscription", type="boolean")
     */
    protected $fromSubscription = false;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="RadnoK\PayUBundle\Model\SubscriberInterface", inversedBy="orders")
     */
    protected $subscriber;

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId)
    {
        $this->orderId = $orderId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount)
    {
        $this->amount = $amount;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getFromSubscription(): bool
    {
        return $this->fromSubscription;
    }

    public function setFromSubscription(bool $fromSubscription)
    {
        $this->fromSubscription = $fromSubscription;
    }

    public function getSubscriber(): SubscriberInterface
    {
        return $this->subscriber;
    }

    public function setSubscriber(SubscriberInterface $subscriber)
    {
        $this->subscriber = $subscriber;
    }

}
