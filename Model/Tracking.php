<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Model;

use Magento\Shipping\Model\Tracking\Result\AbstractResult;

class Tracking extends AbstractResult
{
    const BASE_URL = 'https://inpost.pl/sledzenie-przesylek?number=';

    public function setTracking(string $number): void
    {
        $this->setData('tracking', $number);
    }

    public function getTracking(): ?string
    {
        return $this->getData('tracking');
    }

    public function getUrl(): ?string
    {
        return self::BASE_URL . $this->getTracking();
    }
}
