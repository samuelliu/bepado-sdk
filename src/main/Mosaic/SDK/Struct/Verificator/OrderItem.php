<?php
/**
 * This file is part of the Mosaic SDK Component.
 *
 * @version $Revision$
 */

namespace Mosaic\SDK\Struct\Verificator;

use Mosaic\SDK\Struct\Verificator,
    Mosaic\SDK\Struct\VerificatorDispatcher,
    Mosaic\SDK\Struct;

use Mosaic\SDK\Struct\Product;

/**
 * Visitor verifying integrity of struct classes
 *
 * @version $Revision$
 */
class OrderItem extends Verificator
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
        if (!is_int($struct->count) ||
            $struct->count <= 0) {
            throw new \RuntimeException('Count MUST be a positive integer.');
        }

        if (!$struct->product instanceof Product) {
            throw new \RuntimeException('Product MUST be an instance of \\Mosaic\\SDK\\Struct\\Product.');
        }
        $dispatcher->verify($struct->product);
    }
}