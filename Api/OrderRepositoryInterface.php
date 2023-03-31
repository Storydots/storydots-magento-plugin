<?php

namespace Storydots\VirtualGreeting\Api;

interface OrderRepositoryInterface
{

    /**
     * GET for Post api
     * @param int $orderId
     * @param string $code
     * @param string $storyUrl
     * @param string $qrUrl
     * @param string $tagUrl
     * @return void
     */
    public function updateOrder($orderId, $code, $storyUrl, $qrUrl, $tagUrl);

}