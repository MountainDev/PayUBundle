<?php

namespace RadnoK\PayUBundle\Controller;

use RadnoK\PayUBundle\Entity\Order;
use RadnoK\PayUBundle\Entity\Subscription;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/payment")
 */
class PaymentController extends Controller
{
	/**
	 * @Route("/process", name="radnok_payu_payment_process")
	 * @Method({"POST"})
	 */
	public function processAction(Request $request)
	{

		if (is_object($response) && property_exists($response, 'redirectUri')) {
			return new RedirectResponse($response->redirectUri);
		}

		if (array_key_exists('status', $response) && $response['status'] == 'ok') {
			return $this->redirectToRoute(
			    'radnok_payu_payment_success',
                [
                    'page'  => $response['page'], // @TODO move into url or some other parameter, this is only temporarly
                    'url'   => urlencode($response['url']),
			    ]
            );
		}

        return $this->redirectToRoute('radnok_payu_payment_error');
	}

    /**
     * @Route("/notify", name="radnok_payu_payments_notify")
     */
    public function notifyAction(Request $request)
    {
        $orderStatus = $request['order']['status'];

        if (!in_array($orderStatus, [Order::COMPLETED, Order::CANCELED])) {
            return new JsonResponse(['status' => 'bad status']);
        }

        $orderId = $request['order']['orderId'];

        $entityManager = $this->getDoctrine()->getManager();

        /** @var Order $order */
        $order = $entityManager
            ->getRepository(Order::class)
            ->findOneByOrder($orderId)
        ;

        if ($request['order']['status'] !== Order::COMPLETED) {
            $order->setIsPaid(true);

            $entityManager->persist($order);
            $entityManager->flush();

            if ($subscription = $order->getSubscriber()->getSubscription()) {
                /** @var Subscription $subscription */
                $subscription->setLastPaymentSuccess(new \DateTime());
                $subscription->setChargesFailed(0);

                $entityManager->persist($subscription);
                $entityManager->flush();
            }
        }

        if ($request['order']['status'] === Order::CANCELED) {
            if ($subscription = $order->getSubscriber()->getSubscription()) {
                /** @var Subscription $subscription */
                $subscription->addChargesFailed();

                $entityManager->persist($subscription);
                $entityManager->flush();
            }
        }

        return new JsonResponse(['status' => 'ok']);
    }

    /**
     * @Route("/continue", name="radnok_payu_payments_continue")
     */
    public function continueAction()
    {
        return $this->redirect($this->getParameter('radnok_payu_continue_url'));
    }

    /**
	 * @Route("/{form}/{page}/{url}", name="radnok_payu_payment_success")
	 */
	public function successAction($url)
	{
		return $this->redirect(urldecode($url));
	}
	
	/**
	 * @Route("/error", name="radnok_payu_payment_error")
	 */
	public function errorAction()
	{
		return $this->render('@RadnoKPayU/payment/errors/error.html.twig');
	}
}
