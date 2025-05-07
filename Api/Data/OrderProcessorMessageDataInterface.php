<?php

namespace Markiewiczl\OrderProcessor\Api\Data;

interface OrderProcessorMessageDataInterface
{
    /**
     * @return int
     */
    public function getOrderId(): int;

    /**
     * @param int $orderId
     * @return self
     */
    public function setOrderId(int $orderId): self;
}
