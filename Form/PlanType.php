<?php

namespace RadnoK\PayUBundle\Form;

use RadnoK\PayUBundle\Entity\Plan;
use RadnoK\PayUBundle\Repository\PlanRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'class'         => Plan::class,
                'choice_label'  => 'name',
                'multiple'      => false,
                'expanded'      => true,
                'query_builder' => function (PlanRepository $planRepository) {
                    return $planRepository
                        ->createQueryBuilder('plan')
                        ->where('plan.active = true')
                    ;
                },
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
