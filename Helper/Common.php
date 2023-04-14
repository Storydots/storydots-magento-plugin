<?php
namespace Storydots\VirtualGreeting\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\State;
use Magento\Store\Model\Store;

class Common extends AbstractHelper
{
    private $_state;
    private $_store;
    public function __construct(State $state, Store $store)
    {
        $this->_state = $state;
        $this->_store = $store;
    }
    public function getApiUrl()
    {
        $API_URL = "";
        if ($this->_state->getMode() === State::MODE_DEVELOPER) {
            $API_URL = 'https://api-dev.storydots.app';
        } else {
            $API_URL = 'https://api.storydots.app';
        }
        return $API_URL;
    }
    public function getAppUrl()
    {
        $APP_URL = "";
        if ($this->_state->getMode() === State::MODE_DEVELOPER) {
            $APP_URL = 'https://dev.storydots.app';
        } else {
            $APP_URL = 'https://storydots.app';
        }
        return $APP_URL;
    }
    public function getStoreIdentity()
    {
        $storeUrl      = $this->_store->getBaseUrl();
        $storeIdentity = str_replace("https://", "", $storeUrl);
        // Remove trailing slash
        $storeIdentity = rtrim($storeIdentity, '/');
        return $storeIdentity;
    }
}