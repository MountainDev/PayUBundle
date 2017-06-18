<?php

namespace RadnoK\PayUBundle\Twig;

class PayUExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('payu_widget', [$this, 'widget'], [
                'needs_environment' => true,
                'is_safe' => ['html']
            ]),
        ];
    }

    public function widget(\Twig_Environment $environment, $params, $sig)
    {
        return $environment->render(
            'PayUBundle:Recurring:widget.html.twig',
            [
                'params'    => $params,
                'sig'       => $sig
            ]
        );
    }
}