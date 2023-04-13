<?php

namespace Storydots\VirtualGreeting\Controller\Adminhtml\Order;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Storydots\VirtualGreeting\Helper\Common;

/**
 * Class MassDelete
 */
class PrintTags extends \Magento\Sales\Controller\Adminhtml\Order\AbstractMassAction
{
    /**
     * @var OrderManagementInterface
     */
    protected $orderManagement;
    protected $storeManager;

    protected $logger;
    protected $sdHelper;
    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param OrderManagementInterface $orderManagement
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        OrderManagementInterface $orderManagement,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        Common $sdHelper
    ) {
        parent::__construct($context, $filter);
        $this->collectionFactory = $collectionFactory;
        $this->orderManagement   = $orderManagement;
        $this->storeManager      = $storeManager;
        $this->logger            = $logger;
        $this->sdHelper          = $sdHelper;
    }

    /**
     * Hold selected orders
     *
     * @param AbstractCollection $collection
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    protected function massAction(AbstractCollection $collection)
    {
        $ids = [];
        foreach ($collection->getItems() as $order) {
            if ($order->getEntityId() && $order->getData("storydots_virtual_greeting")) {
                $ids[] = $order->getEntityId();
            }
        }
        $urlParams = array(
            'ids' => implode(",", $ids),
            'store' => $this->sdHelper->getStoreIdentity()
        )
        ;
        $redirectUrl    = $this->sdHelper->getApiUrl() . '/mgPrint?' . http_build_query($urlParams);
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setUrl($redirectUrl);
        return $resultRedirect;

    }
}