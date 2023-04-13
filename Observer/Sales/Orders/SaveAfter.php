<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Storydots\VirtualGreeting\Observer\Sales\Orders;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Psr\Log\LoggerInterface;
use Storydots\VirtualGreeting\Helper\Common;

;
use \Exception;



class SaveAfter implements ObserverInterface
{
    private $logger;
    private $sdHelper;
    public function __construct(
        LoggerInterface $logger,
        Common $sdHelper
    ) {
        $this->logger   = $logger;
        $this->sdHelper = $sdHelper;
    }

    public function execute(Observer $observer)
    {
        try {

            $STORYDOTS_NOTIFY_URL = $this->sdHelper->getApiUrl() . "/magento/notify";
            /** @var \Magento\Sales\Model\Order $order */
            $order          = $observer->getEvent()->getOrder();
            $storeUrl       = $order->getStore()->getBaseUrl();
            $clientIdentity = str_replace("https://", "", $storeUrl);
            // Remove trailing slash
            $clientIdentity = rtrim($clientIdentity, '/');

            if (
                $order->getState() == Order::STATE_PROCESSING
                && $order->getOrigData()["state"] != Order::STATE_PROCESSING
            ) {
                $orderData                 = [];
                $orderData['order_id']     = $order->getId();
                $orderData['store_id']     = $clientIdentity;
                $hasGreeting               = filter_var($order->getData("storydots_virtual_greeting"), FILTER_VALIDATE_BOOLEAN);
                $orderData['has_greeting'] = $hasGreeting;
                foreach ($order->getAllItems() as $item) {
                    $orderData['items'][] = ['sku' => $item->getSku(), 'quantity' => $item->getQtyInvoiced(), 'price' => $item->getPrice(), 'product_name' => $item->getName()];
                }
                $orderData['total']        = $order->getTotalInvoiced();
                $orderData['buyer_email']  = urlencode($order->getCustomerEmail());
                $orderData['buyer_name']   = $order->getCustomerName();
                $orderData['order_number'] = $order->getIncrementId();
                $orderData['created_at']   = $order->getCreatedAt();
                $orderData['cart_id']      = $order->getQuoteId();
                
                $requestBody               = json_encode($orderData);

                $headers = [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($requestBody)
                ];

                $curl = curl_init($STORYDOTS_NOTIFY_URL);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_exec($curl);
                $httpStatusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                curl_close($curl);

                if ($httpStatusCode !== 200) {
                    $this->logger->error("HTTP Status Code >> {$httpStatusCode}");
                    throw new Exception("Error notifying storydots", 1);
                }
                $this->logger->info("Order notified to storydots successfully");
            }

        } catch (\Throwable $th) {
            $this->logger->error($th);
        }
        return;
    }
}