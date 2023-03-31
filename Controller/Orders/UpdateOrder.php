<?php

namespace Storydots\VirtualGreeting\Controller\Orders;

use Storydots\VirtualGreeting\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Sales\Model\OrderRepository;

class UpdateOrder implements OrderRepositoryInterface
{
    protected $logger;
    protected $orderRepository;
    protected $order;
    protected $orderFactory;
    protected $orderModel;
    protected $orderResourceModel;
    public function __construct(
        LoggerInterface $logger,
        OrderRepository $orderRepository,
    )
    {
        $this->logger          = $logger;
        $this->orderRepository = $orderRepository;
    }
    /**
     * @param int $orderId
     * @param string $code
     * @param string $storyUrl
     * @param string $qrUrl
     * @param string $tagUrl
     * @return string
     * @inheritdoc
     */
    public function updateOrder($orderId, $code, $storyUrl, $qrUrl, $tagUrl)
    {

        $response = ['success' => false];

        try {

            $order = $this->orderRepository->get($orderId);

            $order->setData("storydots_code", $code);
            $order->setData("storydots_story_url", $storyUrl);
            $order->setData("storydots_qr_url", $qrUrl);
            $order->setData("storydots_tag_url", $tagUrl);

            $this->orderRepository->save($order);

            $response = ['success' => true, 'message' => "updated order successfully"];

        } catch (\Exception $e) {

            $response = ['success' => false, 'message' => $e->getMessage()];

            $this->logger->error($e->getMessage());

        }

        $returnArray = json_encode($response);

        return $returnArray;

    }

}