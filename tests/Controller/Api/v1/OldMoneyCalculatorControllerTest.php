<?php

namespace App\Tests\Controller\Api\v1;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class OldMoneyCalculatorControllerTest extends TestCase {

    protected $baseUrl = '/api/v1/oldMoneyCalculator';
    protected $client;

    function setUp(): void {
        $this->client = new Client([
            'base_uri' => 'http://nginx',
            'defaults' => [
                'exceptions' => true
            ]
        ]);
    }

    protected function tearDown(): void {
        $this->client = null;
    }

    /**
     * @throws GuzzleException
     */
    public function testAddMoney(): void {

        $response = $this->client->get($this->baseUrl.'/sum', [
            "query" => [
                'firstValue' => '5p17s8d',
                'secondValue' => '3p4s10d',
            ]
        ]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJsonResponse($response->getBody(), [
            'result' => '9p2s6d',
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function testSubtractMoney() {
        $response = $this->client->get($this->baseUrl.'/subtraction', [
            "query" => [
                'firstValue' => '5p17s8d',
                'secondValue' => '3p4s10d',
            ]
        ]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJsonResponse($response->getBody(), [
            'result' => '2p12s10d',
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function testMultiplyMoneyByInteger() {
        $response = $this->client->get($this->baseUrl.'/multiplication', [
            "query" => [
                'firstValue' => '5p17s8d',
                'multiplier' => '3',
            ]
        ]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJsonResponse($response->getBody(), [
            'result' => '17p13s0d',
        ]);
    }

    public function testDivideMoneyWithRemainder() {
        $response = $this->client->get($this->baseUrl.'/division', [
            "query" => [
                'firstValue' => '18p16s1d',
                'divider' => '15',
            ]
        ]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJsonResponse($response->getBody(), [
            'result' => '1p5s0d',
            'remainder' => '0p1s1d',
        ]);
    }

    // Test: Divisione per zero

    /**
     * @throws GuzzleException
     */
    public function testDivideByZero() {
        try {
            $this->client->get($this->baseUrl.'/division', [
                "query" => [
                    'firstValue' => '18p16s1d',
                    'divider' => '0',
                ]
            ]);

        } catch (GuzzleException $e) {
            $response = $e->getResponse();
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
            $responseDecodified = json_decode($response->getBody()->getContents(), true);
            $this->assertArrayHasKey("error", $responseDecodified);
            $this->assertArrayHasKey("errors", $responseDecodified);
        }
    }

    // Test: Somma di numeri grandi
    public function testAddLargeNumbers() {
        $response = $this->client->get($this->baseUrl.'/sum', [
            "query" => [
                'firstValue' => '9999999p59s999d',
                'secondValue' => '1000000p40s5d',
            ]
        ]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJsonResponse($response->getBody(), [
            'result' => '11000008p2s8d',
        ]);
    }

    // Test: Sottrazione con numeri negativi (debito)
    public function testSubtractNegativeResult() {
        try {
            $this->client->get($this->baseUrl.'/subtraction', [
                "query" => [
                    'firstValue' => '5p17s8d',
                    'secondValue' => '6p4s10d',
                ]
            ]);
        } catch (GuzzleException $e) {
            $response = $e->getResponse();
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
            $responseDecodified = json_decode($response->getBody()->getContents(), true);
            $this->assertArrayHasKey("error", $responseDecodified);
        }
    }

    // Test: Moltiplicazione con zero

    /**
     * @throws GuzzleException
     */
    public function testMultiplyByZero() {

        $response = $this->client->get($this->baseUrl.'/multiplication', [
            "query" => [
                'firstValue' => '18p16s1d',
                'multiplier' => '0',
            ]
        ]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJsonResponse($response->getBody(), [
            'result' => '0p0s0d',
        ]);
    }

    // Test: Divisione con resto (valore piccolo)
    public function testDivideWithSmallRemainder() {
        try {
            $this->client->get($this->baseUrl.'/division', [
                "query" => [
                    'firstValue' => '18p16s1d',
                    'divider' => '0',
                ]
            ]);
        } catch (GuzzleException $e) {
            $response = $e->getResponse();
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
            $responseDecodified = json_decode($response->getBody()->getContents(), true);
            $this->assertArrayHasKey("error", $responseDecodified);
        }
    }

    // Test: Somma di valori con formati errati
    public function testAddInvalidFormat() {
        try {
            $this->client->get($this->baseUrl.'/sum', [
                "query" => [
                    'firstValue' => '5p10x8d', // Formato errato
                    'secondValue' => '3p4s10d',
                ]
            ]);
        } catch (GuzzleException $e) {
            $response = $e->getResponse();
            $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        }
    }

// Funzione di utilità per verificare la risposta JSON
    private function assertJsonResponse(
        $response,
        $expectedData
    ) {
        $data = json_decode($response, true);
        $this->assertEquals($expectedData, $data);
    }
}
