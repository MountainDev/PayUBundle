<?php
/**
 * Created by radnok <alfaro.konrad@gmail.com>.
 * Date: 20.06.2017
 */

namespace RadnoK\PayUBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use RadnoK\PayUBundle\Manager\OrderManager as BaseOrderManager;
use RadnoK\PayUBundle\Model\OrderInterface;

class OrderManager extends BaseOrderManager
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

    /**
     * @return ObjectRepository
     */
    protected function getRepository()
    {
        return $this->objectManager->getRepository($this->getClass());
    }

    /**
     * @return string
     */
    public function getClass()
    {
        if (false !== strpos($this->class, ':')) {
            $metadata = $this->objectManager->getClassMetadata($this->class);
            $this->class = $metadata->getName();
        }

        return $this->class;
    }

    public function findOrderBy($criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    public function create(): OrderInterface
    {
        return new $this->class;
    }

    public function update(OrderInterface $order)
    {
        $this->objectManager->persist($order);
        $this->objectManager->flush();
    }
}
