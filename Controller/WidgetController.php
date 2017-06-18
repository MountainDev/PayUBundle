<?php

namespace RadnoK\PayUBundle\Controller;

use RadnoK\PayUBundle\Entity\Order;
use RadnoK\PayUBundle\Entity\Subscription;
use RadnoK\PayUBundle\Utils\Signature;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/widget")
 */
class WidgetController extends Controller
{

    /**
     * @Route("/process", name="radnok_payu_payment_widget")
     * @Method({"POST"})
     */
    public function widgetAction(Request $request)
    {
        $language = !empty($request->get('language')) ? $request->get('amount') : 'pl';

        $params = [
            'merchant-pos-id'   => $this->getParameter('payu_client_id'),
            'shop-name'         => $this->getParameter('payu_service_name'),
            'total-amount'      => $request->get('amount'),
            'currency-code'     => 'PLN',
            'customer-language' => $language,
            'store-card'        => 'true',
            'recurring-payment' => 'true',
            'customer-email'    => $request->get('email'),
            'payu-brand'        => 'false',
            'widget-mode'       => 'pay'
        ];

        $signature = Signature::generate($params, $this->getParameter('payu_signature_key'));

        return $this->render(
            '@RadnoKPayU/payment/recurring/widget.html.twig',
            [
                'params'    => $params,
                'sig'       => $signature,
            ]
        );
    }
}
