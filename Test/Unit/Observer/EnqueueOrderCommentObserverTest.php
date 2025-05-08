<?php

declare(strict_types=1);

namespace Markiewiczl\OrderProcessor\Test\Unit\Observer;

use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use Magento\Sales\Api\Data\OrderInterface;
use Markiewiczl\OrderProcessor\Api\OrderPublisherInterface;
use Markiewiczl\OrderProcessor\Observer\EnqueueOrderCommentObserver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class EnqueueOrderCommentObserverTest extends TestCase
{
    /**
     * @var MockObject
     */
    private MockObject $publisherMock;

    /**
     * @var MockObject
     */
    private MockObject $loggerMock;

    /**
     * @var EnqueueOrderCommentObserver
     */
    private EnqueueOrderCommentObserver $observerInstance;

    protected function setUp(): void
    {
        $this->publisherMock = $this->createMock(OrderPublisherInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        $this->observerInstance = new EnqueueOrderCommentObserver(
            $this->publisherMock,
            $this->loggerMock
        );
    }

    public function testExecuteLogsWarningWhenOrderIsMissing()
    {
        // Arrange
        $eventMock = $this->createMock(Event::class);
        $eventMock->method('getData')->with('order')->willReturn(null);

        $observerMock = $this->createMock(Observer::class);
        $observerMock->method('getEvent')->willReturn($eventMock);

        $this->loggerMock->expects($this->once())
            ->method('warning')
            ->with($this->stringContains('During processing error appears'));

        $this->publisherMock->expects($this->never())->method('publish');

        // Act
        $this->observerInstance->execute($observerMock);
    }

    public function testExecutePublishesOrderAndLogsInfo()
    {
        // Arrange
        $orderId = 777;
        $orderMock = $this->createMock(OrderInterface::class);
        $orderMock->method('getEntityId')->willReturn($orderId);

        $eventMock = $this->createMock(Event::class);
        $eventMock->method('getData')->with('order')->willReturn($orderMock);

        $observerMock = $this->createMock(Observer::class);
        $observerMock->method('getEvent')->willReturn($eventMock);

        $this->publisherMock->expects($this->once())
            ->method('publish')
            ->with($orderMock);

        $this->loggerMock->expects($this->once())
            ->method('info')
            ->with($this->stringContains("Order {$orderId} was published!"));

        // Act
        $this->observerInstance->execute($observerMock);
    }
}
