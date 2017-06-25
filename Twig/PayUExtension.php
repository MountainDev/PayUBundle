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

    public function widget(\Twig_Environment $environment, array $params, string $sig, string $env)
    {
        return $environment->render(
            '@RadnoKPayU/payment/recurring/widget.html.twig',
            [
                'env'       => $env,
                'params'    => $params,
                'sig'       => $sig
            ]
        );
    }
}
