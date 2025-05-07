<?php

namespace Markiewiczl\OrderProcessor\Api;

use Magento\Sales\Api\Data\OrderInterface;

interface OrderPublisherInterface
{
    /**
     * @param OrderInterface $order
     * @return void
     */
    public function publish(OrderInterface $order): void;
}
