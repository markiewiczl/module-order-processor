<?php

declare(strict_types=1);

namespace Markiewiczl\OrderProcessor\Test\Unit\Model;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Markiewiczl\OrderProcessor\Api\ConfigInterface;
use Markiewiczl\OrderProcessor\Api\Data\OrderProcessorMessageDataInterface;
use Markiewiczl\OrderProcessor\Model\Queue\Handler\OrderProcessorHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class OrderProcessorHandlerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private MockObject $orderRepositoryMock;

    /**
     * @var MockObject
     */
    private MockObject $configMock;

    /**
     * @var MockObject
     */
    private MockObject $loggerMock;

    /**
     * @var OrderProcessorHandler
     */
    private OrderProcessorHandler $consumer;

    protected function setUp(): void
    {
        $this->orderRepositoryMock = $this->createMock(OrderRepositoryInterface::class);
        $this->configMock = $this->createMock(ConfigInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        $this->consumer = new OrderProcessorHandler(
            $this->orderRepositoryMock,
            $this->configMock,
            $this->loggerMock
        );
    }

    public function testProcessWithInvalidOrderLogsWarningAndDoesNotSave()
    {
        //Arrange
        $orderProcessorMessageDataMock = $this->createMock(OrderProcessorMessageDataInterface::class);
        $orderId = 1;

        $orderProcessorMessageDataMock->method('getOrderId')->willReturn($orderId);
        $this->orderRepositoryMock->method('get')
            ->with($orderId)
            ->willReturn(null);


        $this->loggerMock
            ->expects($this->once())
            ->method('warning')
            ->with($this->stringContains('Could not publish order'));

        $this->orderRepositoryMock->expects($this->never())->method('save');

        //Act
        $this->consumer->execute($orderProcessorMessageDataMock);
    }

    public function testProcessWithValidOrderIdAddsComment()
    {
        //Arrange
        $orderProcessorMessageDataMock = $this->createMock(OrderProcessorMessageDataInterface::class);
        $orderMock = $this->createMock(Order::class);
        $orderId = 1;
        $comment = 'Test comment';

        $orderProcessorMessageDataMock->method('getOrderId')
            ->willReturn($orderId);
        $this->orderRepositoryMock->method('get')
            ->with($orderId)
            ->willReturn($orderMock);
        $this->configMock->method('getCommentText')
            ->willReturn($comment);
        $orderMock->method('addCommentToStatusHistory')
            ->with($comment);
        $this->orderRepositoryMock->method('save')
            ->with($orderMock);

        //Act
        $this->consumer->execute($orderProcessorMessageDataMock);
    }
}
