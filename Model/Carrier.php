<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Model;

use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Directory\Helper\Data;
use Magento\Directory\Model\CountryFactory;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Xml\Security;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory as RateErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrierOnline;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Rate\ResultFactory as RateResultFactory;
use Magento\Shipping\Model\Simplexml\ElementFactory;
use Magento\Shipping\Model\Tracking\Result as TrackingResult;
use Magento\Shipping\Model\Tracking\Result\ErrorFactory as TrackingErrorFactory;
use Magento\Shipping\Model\Tracking\Result\StatusFactory;
use Magento\Shipping\Model\Tracking\ResultFactory as TrackingResultFactory;
use MB\Inpost\Action\GetContainerTypesOptions;
use MB\Inpost\Action\RequestShipment;
use Psr\Log\LoggerInterface;

class Carrier extends AbstractCarrierOnline implements CarrierInterface
{
    protected $_code = 'mbinpost';

    private Config\Source\Method $methodSource;

    private RequestShipment $requestShipment;

    private GetContainerTypesOptions $getContainerTypesOptions;

    private TrackingFactory $trackingFactory;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        RateErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        Security $xmlSecurity,
        ElementFactory $xmlElFactory,
        RateResultFactory $rateFactory,
        MethodFactory $rateMethodFactory,
        TrackingResultFactory $trackFactory,
        TrackingErrorFactory $trackErrorFactory,
        StatusFactory $trackStatusFactory,
        RegionFactory $regionFactory,
        CountryFactory $countryFactory,
        CurrencyFactory $currencyFactory,
        Data $directoryData,
        StockRegistryInterface $stockRegistry,
        Config\Source\Method $methodSource,
        RequestShipment $requestShipment,
        GetContainerTypesOptions $getContainerTypesOptions,
        TrackingFactory $trackingFactory,
        array $data = []
    ) {
        parent::__construct(
            $scopeConfig,
            $rateErrorFactory,
            $logger,
            $xmlSecurity,
            $xmlElFactory,
            $rateFactory,
            $rateMethodFactory,
            $trackFactory,
            $trackErrorFactory,
            $trackStatusFactory,
            $regionFactory,
            $countryFactory,
            $currencyFactory,
            $directoryData,
            $stockRegistry,
            $data
        );
        $this->methodSource = $methodSource;
        $this->requestShipment = $requestShipment;
        $this->getContainerTypesOptions = $getContainerTypesOptions;
        $this->trackingFactory = $trackingFactory;
    }

    /**
     * @param  RateRequest $request
     * @return Result|bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }
        /** @var Result $result */
        $result = $this->_rateFactory->create();
        foreach ($this->getAllowedMethods() as $code => $title) {
            $method = $this->createResultMethod($code, $title);
            $result->append($method);
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getAllowedMethods(): array
    {
        $values = [];
        $allowedMethods = explode(',', $this->getConfigData('allowed_methods'));
        foreach ($allowedMethods as $code) {
            $values[$code] = $this->getMethodName($code);
        }
        return $values;
    }

    public function getContainerTypes(?DataObject $params = null): array
    {
        return $this->getContainerTypesOptions->execute($params);
    }

    /**
     * @param string|string[] $trackings
     * @return TrackingResult
     */
    public function getTracking($trackings): TrackingResult
    {
        if (!\is_array($trackings)) {
            $trackings = [$trackings];
        }
        $trackingResult = $this->_trackFactory->create();
        foreach ($trackings as $number) {
            /** @var Tracking $tracking */
            $tracking = $this->trackingFactory->create();
            $tracking->setTracking($number);
            $trackingResult->append($tracking);
        }
        return $trackingResult;
    }

    /**
     * @param string $methodCode
     * @param string $methodTitle
     * @return Method
     */
    private function createResultMethod(string $methodCode, string $methodTitle): Method
    {
        /** @var Method $method */
        $method = $this->_rateMethodFactory->create();
        $method->setCarrier('mbinpost');
        $method->setCarrierTitle($this->getConfigData('title'));
        $method->setMethod($methodCode);
        $method->setMethodTitle($methodTitle);
        $price = $this->getConfigData($methodCode . '_price');
        $method->setPrice($price);
        $method->setCost($price);
        return $method;
    }

    /**
     * @param string $code
     * @return string
     */
    private function getMethodName(string $code): string
    {
        $methods = $this->methodSource->toOptionArray();
        foreach ($methods as $method) {
            if ($method['value'] === $code) {
                return (string) $method['label'];
            }
        }
        return '';
    }

    protected function _doShipmentRequest(DataObject $request): DataObject
    {
        return $this->requestShipment->execute($request);
    }
}
