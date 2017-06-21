<?php

namespace RadnoK\PayUBundle\Command;

use RadnoK\PayUBundle\Handler\ChargeCard;
use RadnoK\PayUBundle\Handler\Charges\CardPayment;
use RadnoK\PayUBundle\Manager\CardManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CardsChargeCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('payu:charge-cards')
            ->setDescription('Charge all available cards registered in PayU service')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
