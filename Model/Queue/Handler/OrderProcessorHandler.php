<?php

declare(strict_types=1);

namespace Markiewiczl\OrderProcessor\Model\Queue\Handler;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Markiewiczl\OrderProcessor\Api\ConfigInterface;
use Markiewiczl\OrderProcessor\Api\Data\OrderProcessorMessageDataInterface;
use Psr\Log\LoggerInterface;

class OrderProcessorHandler
{
    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param ConfigInterface $config
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ConfigInterface $config,
        private readonly LoggerInterface $logger
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

        if (!$order instanceof OrderInterface) {
            $this->logger->warning(
                'OrderPlacedPublisher: Could not publish order – order object missing or has no ID.'
            );

            return;
        }

        $order->addCommentToStatusHistory($this->config->getCommentText());
        $this->orderRepository->save($order);

        $this->logger->info(
            sprintf("OrderPlacedPublisher: Published order ID %s to queue.", $order->getEntityId())
        );
    }
}
