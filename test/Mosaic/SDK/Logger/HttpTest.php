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
    public function testLog()
    {
        $order = new Struct\Order(array(
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

        $logger = new Http(
            $httpClient = $this->getMock('\\Mosaic\\SDK\\HttpClient')
        );

        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                '/log',
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
     * @expectedException \RuntimeException
     */
    public function testLoggingFailed()
    {
        $order = new Struct\Order();
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
