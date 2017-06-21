<?php
/**
 * Created by radnok <alfaro.konrad@gmail.com>.
 * Date: 19.06.2017
 */

namespace RadnoK\PayUBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;

class PlanManager
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $class;

    public function __construct(ObjectManager $objectManager, $class)
    {
        $this->objectManager = $objectManager;
        $this->class = $class;
    }

    protected function getRepository()
    {
        return $this->objectManager->getRepository($this->getClass());
    }

    public function getClass()
    {
        if (false !== strpos($this->class, ':')) {
            $metadata = $this->objectManager->getClassMetadata($this->class);

            $this->class = $metadata->getName();
        }

        return $this->class;
    }

    public function getByCode($criteria)
    {
        $this->getRepository()->findBy($criteria);
    }
}
