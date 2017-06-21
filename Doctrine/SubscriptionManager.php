<?php
/**
 * Created by radnok <alfaro.konrad@gmail.com>.
 * Date: 20.06.2017
 */

namespace RadnoK\PayUBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use RadnoK\PayUBundle\Manager\SubscriptionManagerInterface;
use RadnoK\PayUBundle\Model\SubscriptionInterface;

class SubscriptionManager implements SubscriptionManagerInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var string
     */
    private $class;

    public function __construct(ObjectManager $objectManager, string $class)
    {
        $this->objectManager = $objectManager;
        $this->class = $class;
    }

    public function getClass()
    {
        if (false !== strpos($this->class, ':')) {
            $metadata = $this->objectManager->getClassMetadata($this->class);
            $this->class = $metadata->getName();
        }

        return $this->class;
    }

    public function create(): SubscriptionInterface
    {
        return new $this->class;
    }

    public function update(SubscriptionInterface $subscription)
    {
        $this->objectManager->persist($subscription);
        $this->objectManager->flush();
    }
}
