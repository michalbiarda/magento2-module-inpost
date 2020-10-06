<?php
/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MB\Inpost\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Rate\ResultFactory;
use Psr\Log\LoggerInterface;

class Carrier extends AbstractCarrier implements CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'mb_inpost';

    /**
     * @var ResultFactory
     */
    private $rateResultFactory;

    /**
     * @var MethodFactory
     */
    private $rateMethodFactory;

    /**
     * @var Config\Source\Method
     */
    private $methodSource;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param Config\Source\Method $methodSource
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        Config\Source\Method $methodSource,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->methodSource = $methodSource;
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
        $result = $this->rateResultFactory->create();
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

    /**
     * @param string $methodCode
     * @param string $methodTitle
     * @return Method
     */
    private function createResultMethod(string $methodCode, string $methodTitle): Method
    {
        /** @var Method $method */
        $method = $this->rateMethodFactory->create();
        $method->setCarrier('mb_inpost');
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
                return $method['label'];
            }
        }
        return '';
    }
}
