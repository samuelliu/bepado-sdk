<?php
/**
 * This file is part of the Mosaic SDK Component.
 *
 * @version $Revision$
 */

namespace Mosaic\SDK\Service;

use Mosaic\Common;
use Mosaic\SDK\HttpClient;
use Mosaic\SDK\Gateway;

require_once __DIR__ . '/../bootstrap.php';

class VerificationTest extends Common\Test\TestCase
{
    protected $gateway;

    /**
     * Get used gateway for test
     *
     * @return SDK\Gateway
     */
    protected function getGateway()
    {
        if ($this->gateway) {
            return $this->gateway;
        }

        return $this->gateway = new Gateway\InMemory();
    }

    public function testVerify()
    {
        $verificationService = new Verification(
            $httpClient = $this->getMock('\\Mosaic\\SDK\\HttpClient'),
            $this->getGateway()
        );

        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                '/api/verify',
                '{"apiKey":"apiKey","apiEndpointUrl":"http:\/\/example.com\/endpoint"}',
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

        $verificationService->verify('apiKey', 'http://example.com/endpoint');

        $this->assertSame(
            'shop1',
            $this->getGateway()->getShopId()
        );
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testVerifyFails()
    {
        $verificationService = new Verification(
            $httpClient = $this->getMock('\\Mosaic\\SDK\\HttpClient'),
            $this->getGateway()
        );

        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                '/api/verify',
                '{"apiKey":"apiKey","apiEndpointUrl":"http:\/\/example.com\/endpoint"}',
                array(
                    'Content-Type: application/json',
                )
            )
            ->will(
                $this->returnValue(
                    new HttpClient\Response(
                        array(
                            'status' => 500,
                            'body' => '{"error":"Test Error"}',
                        )
                    )
                )
            );

        $verificationService->verify('apiKey', 'http://example.com/endpoint');

        $this->assertSame(
            'shop1',
            $this->getGateway()->getShopId()
        );
    }
}
