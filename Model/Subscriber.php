<?php
/**
 * Created by radnok <alfaro.konrad@gmail.com>.
 * Date: 18.06.2017
 */

namespace RadnoK\PayUBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


abstract class Subscriber implements SubscriberInterface
{
    protected $id;

    /**
     * @ORM\Column(name="email", type="string")
     * @Assert\Email(message = "radnok.validation.email")
     */
    protected $email;

    /**
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    protected $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    protected $lastName;

    /**
     * @ORM\Column(name="phone_number", type="string", length=15)
     */
    protected $phoneNumber;

    /**
     * @ORM\Column(name="address", type="string", length=255)
     */
    protected $address;

    /**
     * @ORM\OneToOne(targetEntity="RadnoK\PayUBundle\Model\SubscriptionInterface", mappedBy="subscriber")
     */
    protected $subscription;

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getSubscriptions(): ?SubscriptionInterface
    {
        return $this->subscription;
    }

    public function setSubscription(SubscriptionInterface $subscriptions): self
    {
        $this->subscription = $subscriptions;

        return $this;
    }
}
