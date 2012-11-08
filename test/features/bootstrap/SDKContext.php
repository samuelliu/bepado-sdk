<?php

namespace Mosaic\SDK;

use Behat\Behat\Context\BehatContext;

use Mosaic\Common\Rpc;
use Mosaic\Common\Struct;
use Mosaic\SDK\Struct\Product;

use \PHPUnit_Framework_MockObject_Generator as Mocker;

/**
 * Base SDK features context.
 */
class SDKContext extends BehatContext
{
    /**
     * SDK entry point
     *
     * @var SDK
     */
    protected $sdk;

    public function __construct()
    {
        $this->initSDK();
    }

    protected function getGateway()
    {
        $storage = getenv('STORAGE') ?: 'InMemory';
        switch ($storage) {
            case 'InMemory':
                return new Gateway\InMemory();
            case 'MySQLi':
                $config = @parse_ini_file(__DIR__ . '/../../../build.properties');
                $gateway = new Gateway\MySQLi($connection = new MySQLi(
                    $config['db.hostname'],
                    $config['db.userid'],
                    $config['db.password'],
                    $config['db.name']
                ));
                $connection->query('TRUNCATE TABLE mosaic_change;');
                $connection->query('TRUNCATE TABLE mosaic_product;');
                $connection->query('TRUNCATE TABLE mosaic_data;');
                return $gateway;
            default:
                throw new \RuntimeException("Unknown storage backend $storage");
        }
    }

    protected function initSDK()
    {
        $productToShop = Mocker::getMock('\\Mosaic\\SDK\\ProductToShop');
        $productFromShop = Mocker::getMock('\\Mosaic\\SDK\\ProductFromShop');

        $this->sdk = new SDK(
            $this->getGateway(),
            $productToShop,
            $productFromShop
        );
    }

    /**
     * Get fake product for ID
     *
     * @param int $productId
     * @return Product
     */
    protected function getProduct($productId, $data = 'foo')
    {
        return new Product(
            array(
                'shopId' => 'shop-1',
                'sourceId' => (string) $productId,
                'title' => $data,
                'price' => $productId * .89,
                'currency' => 'EUR',
                'availability' => $productId,
            )
        );
    }
}
