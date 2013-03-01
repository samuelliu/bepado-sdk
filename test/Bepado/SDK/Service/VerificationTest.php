<?php
/**
 * This file is part of the Bepado SDK Component.
 *
 * @version $Revision$
 */

namespace Bepado\SDK\Service;

use Bepado\Common;
use Bepado\SDK\HttpClient;
use Bepado\SDK\Gateway;

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
            $httpClient = $this->getMock('\\Bepado\\SDK\\HttpClient'),
            $this->getGateway()
        );

        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                '/sdk/verify',
                '{"apiKey":"apiKey","apiEndpointUrl":"http:\/\/example.com\/endpoint"}'
            )
            ->will(
                $this->returnValue(
                    new HttpClient\Response(
                        array(
                            'status' => 200,
                            'body' => '{"shopId":"shop1","categories":{"/others":"Others"}}',
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
            $httpClient = $this->getMock('\\Bepado\\SDK\\HttpClient'),
            $this->getGateway()
        );

        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                '/sdk/verify',
                '{"apiKey":"apiKey","apiEndpointUrl":"http:\/\/example.com\/endpoint"}'
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

    public function testVerifyAgainstRealService()
    {
        $verificationService = new Verification(
            new HttpClient\Stream('http://socialnetwork.bepado.local/'),
            $this->getGateway()
        );

        $verificationService->verify(
            'fd4a49b0-623c-4142-bd09-871d4ccd86f0',
            'http://shop.bepado.local/kore/rpc.php?' . time()
        );

        $this->assertEquals(
            3,
            $this->getGateway()->getShopId()
        );
    }
}