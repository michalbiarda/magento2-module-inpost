<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Model;

class ContainerType
{
    private string $templateName;
    private string $label;
    private float $width;
    private float $height;
    private float $length;
    private float $weight;

    public function __construct(
        string $templateName,
        string $label,
        float $width,
        float $height,
        float $length,
        float $weight
    ) {
        $this->templateName = $templateName;
        $this->label = $label;
        $this->width = $width;
        $this->height = $height;
        $this->length = $length;
        $this->weight = $weight;
    }

    public function getTemplateName(): string
    {
        return $this->templateName;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function getHeight(): float
    {
        return $this->height;
    }

    public function getLength(): float
    {
        return $this->length;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }
}
