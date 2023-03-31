<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Storydots\VirtualGreeting\Observer\Sales\Orders;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;


class SubmitBefore implements ObserverInterface
{
    private $logger;
    public function __construct(
        LoggerInterface $logger,
    ) {
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        try {
            $event = $observer->getEvent();
            // Get Order Object
            /* @var $order \Magento\Sales\Model\Order */
            $order = $event->getOrder();
            // Get Quote Object
            /** @var $quote \Magento\Quote\Model\Quote $quote */
            $quote       = $event->getQuote();
            $hasGreeting = (boolean) $quote->getData("storydots_virtual_greeting");

            if ($hasGreeting) {
                $order->setData("storydots_virtual_greeting", $hasGreeting);
            }

        } catch (\Throwable $th) {
            $this->logger->error($th);
        }
    }
}