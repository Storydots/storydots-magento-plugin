<?php
namespace Storydots\VirtualGreeting\Block;

use \Magento\Checkout\Block\Cart;
use Magento\Store\Model\StoreManagerInterface;
use Storydots\VirtualGreeting\Helper\Common;

class Data extends \Magento\Framework\View\Element\Template
{
    protected $storeManager;
    protected $sdHelper;
    protected $checkoutCartBlock;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        StoreManagerInterface $storeManager,
        Cart $checkoutCartBlock,
        Common $sdHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->storeManager      = $storeManager;
        $this->sdHelper          = $sdHelper;
        $this->checkoutCartBlock = $checkoutCartBlock;
    }

    public function getStoreIdentity()
    {
        return $this->sdHelper->getStoreIdentity();
    }
    public function getQuoteId()
    {
        return $this->checkoutCartBlock->getQuote()->getId();
    }
}