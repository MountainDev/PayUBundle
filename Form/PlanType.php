<?php

namespace RadnoK\PayUBundle\Form;

use RadnoK\PayUBundle\Entity\Plan;
use RadnoK\PayUBundle\Model\PlanInterface;
use RadnoK\PayUBundle\Repository\PlanRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanType extends AbstractType
{
    /**
     * @var string
     */
    private $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'class'         => $this->class,
                'choice_label'  => 'name',
                'multiple'      => false,
                'expanded'      => true,
            ]
        );
    }

    public function getBlockPrefix()
    {
        return 'radnok_payu_plan';
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
