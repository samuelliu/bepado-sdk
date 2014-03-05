<?php
/**
 * This file is part of the Bepado SDK Component.
 *
 * @version $Revision$
 */

namespace Bepado\SDK\Struct\Metric;

use \Bepado\SDK\Struct\Metric;

/**
 * Count metric
 *
 * @version $Revision$
 */
class Count extends Metric
{
    /**
     * Count value
     *
     * @var int
     */
    public $count;

    public function __toString()
    {
        return sprintf(
            " METRIC_COUNT metric=%s value=%d ",
            $this->name,
            $this->count
        );
    }
}