<?php

declare(strict_types=1);

namespace Markiewiczl\OrderProcessor\Model\Queue\Publisher;

use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Markiewiczl\OrderProcessor\Api\Data\OrderProcessorMessageDataInterfaceFactory;
use Markiewiczl\OrderProcessor\Api\Data\OrderProcessorMessageDataInterface;
use Markiewiczl\OrderProcessor\Api\OrderPublisherInterface;

class RabbitOrderPublisher implements OrderPublisherInterface
{
    private const TOPIC_NAME = 'order.processor';

    /**
     * @param PublisherInterface $publisher
     * @param OrderProcessorMessageDataInterfaceFactory $orderProcessorMessageDataFactory
     */
    public function __construct(
        private readonly PublisherInterface $publisher,
        private readonly OrderProcessorMessageDataInterfaceFactory $orderProcessorMessageDataFactory
    ) {
    }

    /**
     * @param OrderInterface $order
     * @return void
     */
    public function publish(OrderInterface $order): void
    {
        $this->publisher->publish(self::TOPIC_NAME, $this->getMessageData($order));
    }

    /**
     * @param OrderInterface $order
     * @return OrderProcessorMessageDataInterface
     */
    private function getMessageData(OrderInterface $order): OrderProcessorMessageDataInterface
    {
        /** @var OrderProcessorMessageDataInterface $messageData */
        $messageData = $this->orderProcessorMessageDataFactory->create();

        $messageData->setOrderId((int) $order->getEntityId());

        return $messageData;
    }
}
