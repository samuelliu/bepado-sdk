<?php
/**
 * This file is part of the Mosaic SDK Component.
 *
 * @version $Revision$
 */

namespace Mosaic\SDK\Gateway;

use Mosaic\SDK\Struct\Product;

/**
 * Gateway interface to maintain changes feed
 *
 * @version $Revision$
 * @api
 */
interface ChangeGateway
{
    /**
     * Get next changes
     *
     * The offset specified the revision to start from
     *
     * May remove all pending changes, which are prior to the last requested
     * revision.
     *
     * @param string $offset
     * @param int $limit
     * @return Struct\Changes[]
     */
    public function getNextChanges($offset, $limit);

    /**
     * Record product insert
     *
     * @param string $id
     * @param string $hash
     * @param string $revision
     * @param Product $product
     * @return void
     */
    public function recordInsert($id, $hash, $revision, Product $product);

    /**
     * Record product update
     *
     * @param string $id
     * @param string $hash
     * @param string $revision
     * @param Product $product
     * @return void
     */
    public function recordUpdate($id, $hash, $revision, Product $product);

    /**
     * Record product delete
     *
     * @param string $id
     * @param string $revision
     * @return void
     */
    public function recordDelete($id, $revision);
}