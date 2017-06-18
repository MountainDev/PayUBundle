<?php

namespace RadnoK\PayUBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RadnoK\CommonBundle\Traits\CreatableAwareTrait;

/**
 * @ORM\Table(name="payu_subscription")
 * @ORM\Entity(repositoryClass="RadnoK\PayUBundle\Repository\SubscriptionRepository")
 */
abstract class Subscription
{
    use CreatableAwareTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="charges_failed", type="integer")
     */
    protected $chargesFailed = 0;

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
    protected $lastPaymentSuccess;

    /**
     * @ORM\ManyToOne(targetEntity="RadnoK\PayUBundle\Entity\Plan", inversedBy="subscriptions")
     */
    protected $plan;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", inversedBy="subscription")
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

    public function getChargesFailed()
    {
        return $this->chargesFailed;
    }

    public function setChargesFailed($chargesFailed)
    {
        $this->chargesFailed = $chargesFailed;

        return $this;
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

    public function getLastPaymentAttempt()
    {
        return $this->lastPaymentAttempt;
    }

    public function setLastPaymentAttempt($lastPaymentAttempt)
    {
        $this->lastPaymentAttempt = $lastPaymentAttempt;

        return $this;
    }

    public function getLastPaymentSuccess()
    {
        return $this->lastPaymentSuccess;
    }

    public function setLastPaymentSuccess($lastPaymentSuccess)
    {
        $this->lastPaymentSuccess = $lastPaymentSuccess;

        return $this;
    }

    public function getPlan()
    {
        return $this->plan;
    }

    public function setPlan($plan)
    {
        $this->plan = $plan;
        
        return $this;
    }

    public function getSubscriber()
    {
        return $this->subscriber;
    }

    public function setSubscriber($subscriber)
    {
        $this->subscriber = $subscriber;
     
        return $this;
    }

}
