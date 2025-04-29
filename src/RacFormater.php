<?php

namespace luismacayo\RacFormater\src;

class RacFormater
{
    /**
     * Initializes the plugin.
     */
    public function init()
    {
        // Initialization code here
    }

    /**
     * Example method for the plugin.
     *
     * @param string $input
     * @return string
     */
    public function format($input)
    {
        // Add your formatting logic here
        return strtoupper($input);
    }

    /**
     * Calculates the price of a product.
     *
     * @param float $basePrice The base price of the product.
     * @param float $taxRate The tax rate as a percentage (e.g., 10 for 10%).
     * @param float $discount The discount amount to subtract.
     * @return float The final price of the product.
     */
    public function calculatePrice($basePrice, $taxRate = 0, $discount = 0)
    {
        $taxAmount = ($basePrice * $taxRate) / 100;
        $finalPrice = $basePrice + $taxAmount - $discount;
        return max($finalPrice, 0); // Ensure the price is not negative
    }
}
