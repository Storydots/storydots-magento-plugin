<?php

namespace Storydots\VirtualGreeting\Api;

interface QuoteRepositoryInterface
{

    /**
     * GET for Post api
     * @param string $virtualGreeting
     * @return string
     */
    public function updateQuote($virtualGreeting);
    public function getQuoteData();

}