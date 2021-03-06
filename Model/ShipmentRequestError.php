<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Model;

use Magento\Framework\DataObject;

class ShipmentRequestError extends DataObject
{
    public function __construct(string $message, array $data = [])
    {
        parent::__construct($data);
        $this->setData('errors', $message);
    }

    public function getErrors(): string
    {
        return $this->getData('errors');
    }
}
