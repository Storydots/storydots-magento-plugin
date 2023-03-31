<?php
namespace Storydots\VirtualGreeting\Plugin\Api;

use Psr\Log\LoggerInterface;
use \Magento\Sales\Api\OrderRepositoryInterface;

class OrderRepository
{

    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function afterGet(OrderRepositoryInterface $subject, $entity)
    {

        $extensionAttributes = $entity->getExtensionAttributes();


        if ($extensionAttributes) {
            $extensionAttributes->setStorydotsCode($entity->getData("storydots_code"));
            $extensionAttributes->setStorydotsQrUrl($entity->getData("storydots_qr_url"));
            $extensionAttributes->setStorydotsTagUrl($entity->getData("storydots_tag_url"));
            $extensionAttributes->setStorydotsStoryUrl($entity->getData("storydots_story_url"));
            $entity->setExtensionAttributes($extensionAttributes);
        }
        return $entity;
    }

    public function beforeSave(OrderRepositoryInterface $subject, $order)
    {
        $extension_attributes = $order->getExtensionAttributes();

        $storydotsCodeData = ["storydots_code", "storydots_story_url", "storydots_qr_url", "storydots_tag_url"];
        $requestData       = file_get_contents('php://input');
        $requestParams     = json_decode($requestData, true);
        foreach ($storydotsCodeData as $key) {
            if (isset($requestParams['entity']['extension_attributes'][$key])) {
                $order->setData($key, $requestParams['entity']['extension_attributes'][$key]);
            }
        }
    }
}