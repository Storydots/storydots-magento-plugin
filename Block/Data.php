<?php
namespace Storydots\VirtualGreeting\Block;

use \Magento\Checkout\Block\Cart;
use Storydots\VirtualGreeting\Helper\Common;
use \Magento\Framework\Locale\Resolver;
use \Magento\Framework\View\Element\Template\Context;

class Data extends \Magento\Framework\View\Element\Template
{
    protected $locale;
    protected $sdHelper;
    protected $checkoutCartBlock;

    public function __construct(
        Context $context,
        Resolver $locale,
        Cart $checkoutCartBlock,
        Common $sdHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->locale            = $locale;
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
    public function getStoreLanguage()
    {
        return $this->locale->getLocale();
    }
}