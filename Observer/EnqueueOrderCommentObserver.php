<?php

declare(strict_types=1);

namespace Markiewiczl\OrderProcessor\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Sales\Api\Data\OrderInterface;
use Markiewiczl\OrderProcessor\Api\OrderPublisherInterface;
use Psr\Log\LoggerInterface;

class EnqueueOrderCommentObserver implements ObserverInterface
{
    /**
     * @param OrderPublisherInterface $publisher
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly OrderPublisherInterface $publisher,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $event = $observer->getEvent();
        $order = $event->getData('order');

        if (!$order instanceof OrderInterface) {
            $this->logger->warning('During processing error appears');

            return;
        }

        $this->publisher->publish($order);
        $this->logger->info(sprintf('Order %s was published!', $order->getEntityId()));
    }
}
