<?php
/**
 * This file is part of the Mosaic SDK Component.
 *
 * @version $Revision$
 */

namespace Mosaic\SDK\Logger;

use Mosaic\Common;
use Mosaic\SDK\Struct;
use Mosaic\SDK\HttpClient;

require_once __DIR__ . '/../bootstrap.php';

class HttpTest extends Common\Test\TestCase
{
    /**
     * Get a valid order struct
     *
     * @return Struct\Order
     */
    protected function getValidOrder()
    {
        return new Struct\Order(array(
            'orderShop' => 'shop1',
            'providerShop' => 'shop2',
            'reservationId' => '42',
            'localOrderId' => 'local',
            'shippingCosts' => 34.43,
            'products' => array(
                new Struct\OrderItem(array(
                    'count' => 2,
                    'product' => array(
                        new Struct\Product(array(
                            'shopId' => 'shop1',
                            'sourceId' => '1-23',
                            'title' => 'Sindelfingen',
                            'price' => 42.23,
                            'currency' => 'EUR',
                            'availability' => 5,
                        ))
                    ),
                ))
            ),
            'deliveryAddress' => new Struct\Address(array(
                'name' => 'Hans Mustermann',
                'line1' => 'Musterstrasse 23',
                'zip' => '12345',
                'city' => 'Musterstadt',
                'country' => 'Germany',
            )),
        ));
    }

    public function testLog()
    {
        $order = $this->getValidOrder();
        $logger = new Http(
            $httpClient = $this->getMock('\\Mosaic\\SDK\\HttpClient')
        );

        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                '/transaction',
                json_encode($order),
                array(
                    'Content-Type: application/json',
                )
            )
            ->will(
                $this->returnValue(
                    new HttpClient\Response(
                        array(
                            'status' => 200,
                            'body' => '{"shopId":"shop1"}',
                        )
                    )
                )
            );

        $logger->log($order);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidOrder()
    {
        $order = new Struct\Order();
        $logger = new Http(
            $httpClient = $this->getMock('\\Mosaic\\SDK\\HttpClient')
        );

        $logger->log($order);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testLoggingFailed()
    {
        $order = $this->getValidOrder();
        $logger = new Http(
            $httpClient = $this->getMock('\\Mosaic\\SDK\\HttpClient')
        );

        $httpClient
            ->expects($this->once())
            ->method('request')
            ->will(
                $this->returnValue(
                    new HttpClient\Response(
                        array(
                            'status' => 500,
                        )
                    )
                )
            );

        $logger->log($order);
    }
}
