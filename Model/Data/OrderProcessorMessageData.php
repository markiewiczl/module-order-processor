<?php

declare(strict_types=1);

namespace Markiewiczl\OrderProcessor\Model\Data;

use Magento\Framework\DataObject;
use Markiewiczl\OrderProcessor\Api\Data\OrderProcessorMessageDataInterface;

class OrderProcessorMessageData extends DataObject implements OrderProcessorMessageDataInterface
{
    private const ORDER_ID = 'order_id';

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return (int) $this->getData(self::ORDER_ID);
    }

    /**
     * @param int $orderId
     * @return OrderProcessorMessageDataInterface
     */
    public function setOrderId(int $orderId): OrderProcessorMessageDataInterface
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }
}
