<?php

namespace Storydots\VirtualGreeting\Controller\Checkout;

use Psr\Log\LoggerInterface;
use Magento\Quote\Model\QuoteRepository;
use Magento\Checkout\Model\Session;
use Storydots\VirtualGreeting\Api\QuoteRepositoryInterface;
use Storydots\VirtualGreeting\Helper\Common;

class Quote implements QuoteRepositoryInterface
{
    protected $logger;
    protected $quoteRepository;
    protected $session;
    protected $sdHelper;
    public function __construct(
        LoggerInterface $logger,
        QuoteRepository $quoteRepository,
        Session $session,
        Common $sdHelper
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->logger          = $logger;
        $this->session         = $session;
        $this->sdHelper        = $sdHelper;

    }


    /**
     * @param boolean $virtualGreeting
     * @return string
     * @inheritdoc
     */
    public function getQuoteData()
    {

        $response = ['success' => false];

        try {
            $quote    = $this->session->getQuote();
            $cart_id  = $quote->getId();
            $store_id = $this->sdHelper->getStoreIdentity();

            if (!$quote)
                throw new \Exception("Error Processing Request", 1);

            $virtualGreetingState = $quote->getData("storydots_virtual_greeting");
            $response             = [
                'success' => true,
                'storydots_virtual_greeting' => filter_var($virtualGreetingState, FILTER_VALIDATE_BOOLEAN),
                'cart_id' => $cart_id,
                'store_id' => $store_id
            ];

        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
            $this->logger->info($e->getMessage());
        }

        $returnArray = json_encode($response);

        return $returnArray;

    }


    /**
     * @param boolean $virtualGreeting
     * @return string
     * @inheritdoc
     */
    public function updateQuote($virtualGreeting)
    {

        $response = ['success' => false];

        try {
            $quote = $this->session->getQuote();

            if (!$quote)
                throw new \Exception("Error Processing Request", 1);

            $virtualGreetingState = $quote->getData("storydots_virtual_greeting");

            $quote->setData("storydots_virtual_greeting", $virtualGreeting);

            $quote->save();

            $response = ['success' => true, 'message' => "quote_storydots_virtual_greeting updated", 'value' => $virtualGreeting];

        } catch (\Exception $e) {

            $response = ['success' => false, 'message' => $e->getMessage()];

            $this->logger->info($e->getMessage());

        }

        $returnArray = json_encode($response);

        return $returnArray;

    }

}