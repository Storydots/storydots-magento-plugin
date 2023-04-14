<?php
namespace Storydots\VirtualGreeting\Block;

use Storydots\VirtualGreeting\Helper\Common;
use \Magento\Framework\View\Element\Template\Context;

class ScriptTag extends \Magento\Framework\View\Element\Template
{
    protected $sdHelper;
    public function __construct(
        Context $context,
        Common $sdHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->sdHelper = $sdHelper;
    }
    public function getScriptURL()
    {
        $baseURL   = $this->sdHelper->getAppUrl();
        $scriptURL = $baseURL . "/tiendanube/script/storydots-magento.js";
        return $scriptURL;
    }
}