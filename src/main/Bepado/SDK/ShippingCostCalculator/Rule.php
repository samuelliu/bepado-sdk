<?php
/**
 * This file is part of the Bepado SDK Component.
 *
 * @version $Revision$
 */

namespace Bepado\SDK\ShippingCostCalculator;

use Bepado\SDK\Struct;

/**
 * Class: Rule
 *
 * Base class for rules to calculate shipping costs for a given order.
 */
abstract class Rule
{
    /**
     * Check if shipping cost is applicable to given order
     *
     * @param Struct\Order $order
     * @return bool
     */
    abstract public function isApplicable(Struct\Order $order);

    /**
     * Get shipping costs for order
     *
     * Returns the net shipping costs.
     *
     * @param Struct\Order $order
     * @return float
     */
    abstract public function getShippingCosts(Struct\Order $order);

    /**
     * If processing should stop after this rule
     *
     * @param Struct\Order $order
     * @return bool
     */
    abstract public function shouldStopProcessing(Struct\Order $order);

    /**
     * Get all values from rule
     *
     * @return array
     */
    abstract public function getState();

    /**
     * Recreate rule from values
     *
     * @param array $values
     * @return Rule
     */
    abstract public function setState(array $values);
}
