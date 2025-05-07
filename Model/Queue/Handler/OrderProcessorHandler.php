<?php

declare(strict_types=1);

namespace Markiewiczl\OrderProcessor\Model\Queue\Handler;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Markiewiczl\OrderProcessor\Api\ConfigInterface;
use Markiewiczl\OrderProcessor\Api\Data\OrderProcessorMessageDataInterface;

class OrderProcessorHandler
{
    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param ConfigInterface $config
     */
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ConfigInterface $config
    ) {
    }

    /**
     * @param OrderProcessorMessageDataInterface $orderProcessorMessageData
     * @return void
     */
    public function execute(OrderProcessorMessageDataInterface $orderProcessorMessageData): void
    {
        /** @var Order $order */
        $order = $this->orderRepository->get($orderProcessorMessageData->getOrderId());
        $order->addCommentToStatusHistory($this->config->getCommentText());
        $this->orderRepository->save($order);
    }
}
