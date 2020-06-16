<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Model;

class RevenueData
{
    /** @var float */
    private $revenue;

    /** @var float|null */
    private $price;

    /** @var int */
    private $quantity;

    /** @var string|null */
    private $productId;

    /** @var string */
    private $revenueType;

    public function __construct(
        float $revenue,
        float $price = null,
        int $quantity = 1,
        string $productId = null,
        string $revenueType = null
    ) {
        $this->revenue = $revenue;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->productId = $productId;
        $this->revenueType = $revenueType;
    }

    public function getRevenue(): float
    {
        return $this->revenue;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getProductId(): ?string
    {
        return $this->productId;
    }

    public function getRevenueType(): string
    {
        return $this->revenueType;
    }

}