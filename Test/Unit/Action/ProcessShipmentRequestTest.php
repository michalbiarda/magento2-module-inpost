<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Test\Unit\Action;

use Magento\Framework\DataObject;
use Magento\Sales\Api\Data\ShipmentInterface;
use MB\Inpost\Action\GetShippingMethodFromShipment;
use MB\Inpost\Action\ProcessShipmentRequestInterface;
use MB\Inpost\Action\RequestShipment;
use MB\Inpost\Exception\ShipmentRequestException;
use MB\Inpost\Model\ShipmentRequestError;
use MB\Inpost\Model\ShipmentRequestResult;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ProcessShipmentRequestTest extends TestCase
{
    private RequestShipment $processShipmentRequest;

    /**
     * @var GetShippingMethodFromShipment|MockObject
     */
    private $getShippingMethodFromShipmentMock;

    /**
     * @var MockObject|LoggerInterface
     */
    private $loggerMock;

    /**
     * @var ProcessShipmentRequestInterface|MockObject
     */
    private $shipmentRequestProcessorMock;

    public function setUp(): void
    {
        $this->getShippingMethodFromShipmentMock = $this->getMockBuilder(GetShippingMethodFromShipment::class)
            ->disableOriginalConstructor()->getMock();
        $this->loggerMock = $this->getMockBuilder(LoggerInterface::class)->getMockForAbstractClass();
        $this->shipmentRequestProcessorMock = $this->getMockBuilder(ProcessShipmentRequestInterface::class)
            ->getMockForAbstractClass();
        $this->processShipmentRequest = new RequestShipment(
            $this->getShippingMethodFromShipmentMock,
            $this->loggerMock,
            ['method_name' => $this->shipmentRequestProcessorMock]
        );
    }

    public function testExecuteReturnsErrorForEmptyRequest(): void
    {
        $result = $this->processShipmentRequest->execute(new DataObject());
        $this->assertInstanceOf(ShipmentRequestError::class, $result);
    }

    public function testExecuteReturnsErrorForRequestWithNotApplicableShipment(): void
    {
        $result = $this->processShipmentRequest->execute(
            new DataObject(['order_shipment' => $this->getShipmentMock('invalid_method')])
        );
        $this->assertInstanceOf(ShipmentRequestError::class, $result);
    }

    public function testExecuteReturnsErrorIfRequestProcessorThrownException(): void
    {
        $this->shipmentRequestProcessorMock->method('execute')
            ->willThrowException(new ShipmentRequestException(__('Message')));
        $result = $this->processShipmentRequest->execute(
            new DataObject(['order_shipment' => $this->getShipmentMock('method_name')])
        );
        $this->assertInstanceOf(ShipmentRequestError::class, $result);
    }

    public function testExecuteReturnsCorrectResponse(): void
    {
        $request = new DataObject();
        $request->setData('order_shipment', $this->getShipmentMock('method_name'));
        $result = $this->processShipmentRequest->execute($request);
        $this->assertInstanceOf(ShipmentRequestResult::class, $result);
    }

    private function getShipmentMock(string $methodName): MockObject
    {
        $this->getShippingMethodFromShipmentMock->method('execute')->willReturn($methodName);
        return $this->getMockBuilder(ShipmentInterface::class)->getMockForAbstractClass();
    }
}
