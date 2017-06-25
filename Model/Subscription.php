<?php

namespace RadnoK\PayUBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @ORM\Table(name="payu_subscription")
 */
abstract class Subscription implements SubscriptionInterface
{
    protected $id;

    /**
     * @ORM\Column(name="token", type="string", length=255, nullable=true)
     */
    protected $token = null;

    /**
     * @ORM\Column(name="last_payment_attempt", type="datetime", nullable=true)
     */
    protected $lastPaymentAttempt = null;

    /**
     * @ORM\Column(name="last_payment_success", type="datetime", nullable=true)
     */
    protected $lastPaymentSuccess = null;

    /**
     * @ORM\Column(name="charges_failed", type="integer")
     */
    protected $chargesFailed = 0;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="RadnoK\PayUBundle\Model\PlanInterface", inversedBy="subscriptions")
     */
    protected $plan;

    /**
     * @ORM\OneToOne(targetEntity="RadnoK\PayUBundle\Model\SubscriberInterface", inversedBy="subscription")
     * @ORM\JoinColumn(name="subscriber_id", referencedColumnName="id")
     */
    protected $subscriber;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    public function getLastPaymentAttempt(): ?\DateTime
    {
        return $this->lastPaymentAttempt;
    }

    public function setLastPaymentAttempt(\DateTime $lastPaymentAttempt)
    {
        $this->lastPaymentAttempt = $lastPaymentAttempt;

        return $this;
    }

    public function getLastPaymentSuccess(): ?\DateTime
    {
        return $this->lastPaymentSuccess;
    }

    public function setLastPaymentSuccess(\DateTime $lastPaymentSuccess)
    {
        $this->lastPaymentSuccess = $lastPaymentSuccess;

        return $this;
    }

    public function getChargesFailed()
    {
        return $this->chargesFailed;
    }

    public function addChargesFailed()
    {
        $this->chargesFailed++;

        return $this;
    }

    public function setChargesFailed(int $chargesFailed)
    {
        $this->chargesFailed = $chargesFailed;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPlan(): PlanInterface
    {
        return $this->plan;
    }

    public function setPlan(PlanInterface $plan)
    {
        $this->plan = $plan;
        
        return $this;
    }

    public function getSubscriber(): SubscriberInterface
    {
        return $this->subscriber;
    }

    public function setSubscriber(SubscriberInterface $subscriber)
    {
        $this->subscriber = $subscriber;
     
        return $this;
    }

}
