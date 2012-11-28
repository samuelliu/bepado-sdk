<?php
/**
 * This file is part of the Mosaic SDK Component.
 *
 * @version $Revision$
 */

namespace Mosaic\SDK\Struct\Verificator\Change;

use Mosaic\SDK\Struct\Verificator\Change;
use Mosaic\SDK\Struct\VerificatorDispatcher;
use Mosaic\SDK\Struct;

use Mosaic\SDK\Struct\Product;

/**
 * Visitor verifying integrity of struct classes
 *
 * @version $Revision$
 */
class InterShopDelete extends Change
{
    /**
     * Method to verify a structs integrity
     *
     * Throws a RuntimeException if the struct does not verify.
     *
     * @param VerificatorDispatcher $dispatcher
     * @param Struct $struct
     * @return void
     */
    public function verify(VerificatorDispatcher $dispatcher, Struct $struct)
    {
        if ($struct->sourceId === null) {
            throw new \RuntimeException('Property $sourceId must be set.');
        }
    }
}