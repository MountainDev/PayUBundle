<?php

namespace RadnoK\PayUBundle\Controller;

use RadnoK\PayUBundle\DBAL\Types\OrderStatusType;
use RadnoK\PayUBundle\Event\ChangePaymentStatusEvent;
use RadnoK\PayUBundle\Model\OrderInterface;
use RadnoK\PayUBundle\RadnoKPayUEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/payment")
 */
class PaymentController extends Controller
{
    /**
     * @Route("/notify", name="radnok_payu_payments_notify")
     * @Method({"POST"})
     */
    public function notifyAction(Request $request)
    {
        $response = json_decode($request->getContent());
        
        if (!property_exists($response, 'order')) {
            return new JsonResponse(['status' => 'Invalid request']);
        }

        $order = $response->order;
        $status = $order->status;

        if (!in_array($status, [OrderStatusType::COMPLETED, OrderStatusType::CANCELED, OrderStatusType::PENDING])) {
            return new JsonResponse(['status' => 'Bad status']);
        }

        $orderManager = $this->get('radnok_payu.order_manager');

        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->get('event_dispatcher');

        /** @var OrderInterface $paymentOrder */
        $paymentOrder = $orderManager->findOrderByOrderId($order->orderId);
        $paymentOrder->setStatus($status);

        $event = new ChangePaymentStatusEvent($paymentOrder, $status);

        switch ($status) {
            case OrderStatusType::PENDING:
                $dispatcher->dispatch(RadnoKPayUEvents::PAYMENT_PENDING, $event);
                break;
            case OrderStatusType::COMPLETED:
                $dispatcher->dispatch(RadnoKPayUEvents::PAYMENT_COMPLETED, $event);
                break;
            case OrderStatusType::CANCELED:
                $dispatcher->dispatch(RadnoKPayUEvents::PAYMENT_CANCELED, $event);
                break;
            default:
                return new JsonResponse(['status' => 'Status cannot be handled']);
        }

        $orderManager->update($paymentOrder);

        return new JsonResponse(['status' => 'ok']);
    }
	
	/**
	 * @Route("/error", name="radnok_payu_payment_error")
	 */
	public function errorAction()
	{
		return $this->render('@RadnoKPayU/payment/errors/error.html.twig');
	}
}
