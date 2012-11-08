<?php
/**
 * This file is part of the Mosaic SDK Component.
 *
 * @version $Revision$
 */

namespace Mosaic\SDK\Service;

use Mosaic\SDK\Gateway;
use Mosaic\SDK\Struct;

/**
 * Service to store configuration updates
 *
 * @version $Revision$
 */
class Configuration
{
    /**
     * Gateway to shop configuration
     *
     * @var Gateway\ShopConfiguration
     */
    protected $configuration;

    /**
     * Struct verificator
     *
     * @var Struct\VerificatorDispatcher
     */
    protected $verificator;

    /**
     * Construct from gateway
     *
     * @param Gateway\ShopConfiguration $gateway
     * @param Struct\VerificatorDispatcher $verificator
     * @return void
     */
    public function __construct(
        Gateway\ShopConfiguration $configuration,
        Struct\VerificatorDispatcher $verificator
    ) {
        $this->configuration = $configuration;
        $this->verificator = $verificator;
    }

    /**
     * Store shop configuration updates
     *
     * @param Struct\ShopConfiguration $shopConfigurations
     * @return void
     */
    public function update(array $shopConfigurations)
    {
        foreach ($shopConfigurations as $shopId => $shopConfiguration) {
            $this->verificator->verify($shopConfiguration);
            $this->configuration->setShopConfiguration($shopId, $shopConfiguration);
        }
    }
}
