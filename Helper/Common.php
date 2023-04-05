<?php
namespace Storydots\VirtualGreeting\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\State;

class Common extends AbstractHelper
{
    private $_state;
    public function __construct(State $state)
    {
        $this->_state = $state;
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
}