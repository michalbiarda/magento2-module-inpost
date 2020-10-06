<?php
/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MB\Inpost\Plugin;

use Magento\Framework\Reflection\FieldNamer;

class FixAddressLineFieldNames
{
    /**
     * This is a dirty fix for Magento not converting methods to field names properly.
     * @param FieldNamer $subject
     * @param $methodName
     * @return string[]
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeGetFieldNameForMethodName(FieldNamer $subject, $methodName): array
    {
        switch ($methodName) {
            case 'getMbInpostLockerAddressLine1':
                return ['getMbInpostLockerAddressLine_1'];
            case 'setMbInpostLockerAddressLine1':
                return ['setMbInpostLockerAddressLine_1'];
            case 'getMbInpostLockerAddressLine2':
                return ['getMbInpostLockerAddressLine_2'];
            case 'setMbInpostLockerAddressLine2':
                return ['setMbInpostLockerAddressLine_2'];
            default:
                return [$methodName];
        }
    }
}
