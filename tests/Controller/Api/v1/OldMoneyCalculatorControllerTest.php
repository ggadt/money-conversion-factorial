<?php

namespace App\Tests\Controller\Api\v1;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class OldMoneyCalculatorControllerTest extends TestCase {

    protected $baseUrl = '/api/v1/oldMoneyCalculator';


    //  // Test: Somma di numeri

    /**
     * @throws GuzzleException
     */
    public function testAddMoney(): void {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://nginx',
            'defaults' => [
                'exceptions' => true
            ]
        ]);

        // Somma di due valori monetari
        $response = $client->get($this->baseUrl.'/sum', [
            "query" => [
                'firstValue' => '5p17s8d',
                'secondValue' => '3p4s10d',
            ]
        ]);
//
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse($response->getBody(), [
            'result' => '9p2s6d',
        ]);
    }
//
    //  // Test: Sottrazione di numeri
    //  public function testSubtractMoney() {
    //      $client = $this->client;
//
//
    //      // Sottrazione di due valori monetari
    //      $client->request('GET', $this->baseUrl.'/subtract', [
    //          'firstValue' => '5p17s8d',
    //          'secondValue' => '3p4s10d',
    //      ]);
//
    //      $this->assertResponseIsSuccessful();
    //      $this->assertJsonResponse($client->getResponse(), [
    //          'result' => '2p12s10d',
    //      ]);
    //  }
//
    //  // Test: Moltiplicazione con un intero
    //  public function testMultiplyMoneyByInteger() {
    //      $client = $this->client;
//
//
    //      // Moltiplicazione di un valore monetario per un intero
    //      $client->request('GET', $this->baseUrl.'/multiplication', [
    //          'firstValue' => '5p17s8d',
    //          'multiplier' => 2,
    //      ]);
//
    //      $this->assertResponseIsSuccessful();
    //      $this->assertJsonResponse($client->getResponse(), [
    //          'result' => '11p15s4d',
    //      ]);
    //  }
//
    //  // Test: Divisione con resto (valore piccolo)
    //  public function testDivideMoneyWithRemainder() {
    //      $client = $this->client;
//
//
    //      // Divisione con resto
    //      $client->request('GET', $this->baseUrl.'/division', [
    //          'firstValue' => '5p17s8d',
    //          'divider' => 2,
    //      ]);
//
    //      $this->assertResponseIsSuccessful();
    //      $this->assertJsonResponse($client->getResponse(), [
    //          'result' => '1p5s0d',
    //          'remainder' => '0p1s1d',
    //      ]);
    //  }
//
    //  // Test: Divisione per zero
    //  public function testDivideByZero() {
    //      $client = $this->client;
//
//
    //      // Divisione per zero
    //      $client->request('GET', $this->baseUrl.'/division', [
    //          'firstValue' => '5p17s8d',
    //          'divisor' => 0,
    //      ]);
//
    //      $this->assertResponseStatusCodeSame(400); // Errore 400 per divisione per zero
    //      $this->assertJsonResponse($client->getResponse(), [
    //          'error' => 'Divisione per zero non consentita',
    //      ]);
    //  }
//
    //  // Test: Somma di numeri grandi
//
    //  public function testAddLargeNumbers() {
    //      $client = $this->client;
//
//
    //      // Somma tra numeri molto grandi
    //      $client->request('GET', $this->baseUrl.'/sum', [
    //          'firstValue' => '9999999p59s999d',
    //          'secondValue' => '1000000p40s5d',
    //      ]);
//
    //      $this->assertResponseIsSuccessful();
    //      $this->assertJsonResponse($client->getResponse(), [
    //          'result' => '10999999p99s1004d',
    //      ]);
    //  }
//
    //  // Test: Sottrazione con numeri negativi (debito)
    //  public function testSubtractNegativeResult() {
    //      $client = $this->client;
//
//
    //      $client->request('GET', $this->baseUrl.'/subtraction', [
    //          'firstValue' => '5p0s0d',
    //          'secondValue' => '5p17s8d',
    //      ]);
//
    //      $this->assertResponseIsSuccessful();
    //      $this->assertJsonResponse($client->getResponse(), [
    //          'result' => '0p0s0d',
    //      ]);
    //  }
//
    //  // Test: Moltiplicazione con zero
    //  public function testMultiplyByZero() {
    //      $client = $this->client;
//
//
    //      // Moltiplicazione per zero
    //      $client->request('GET', $this->baseUrl.'/subtraction', [
    //          'firstValue' => '5p17s8d',
    //          'multiplier' => 0,
    //      ]);
//
    //      $this->assertResponseIsSuccessful();
    //      $this->assertJsonResponse($client->getResponse(), [
    //          'result' => '0p0s0d',
    //      ]);
    //  }
//
    //  // Test: Divisione con resto (valore molto piccolo)
    //  public function testDivideWithSmallRemainder() {
    //      $client = $this->client;
//
//
    //      // Divisione con un resto molto piccolo
    //      $client->request('GET', $this->baseUrl.'/division', [
    //          'firstValue' => '2p1s1d',
    //          'divisor' => 3,
    //      ]);
//
    //      $this->assertResponseIsSuccessful();
    //      $this->assertJsonResponse($client->getResponse(), [
    //          'result' => '0p17s0d',
    //          'remainder' => '0p1s1d',
    //      ]);
    //  }
//
    //  // Test: Somma di valori con formati errati
    //  public function testAddInvalidFormat() {
    //      $client = $this->client;
//
//
    //      // Somma con formato errato
    //      $client->request('GET', $this->baseUrl.'/sum', [
    //          'firstValue' => '5p10x8d', // Formato errato
    //          'secondValue' => '3p4s10d',
    //      ]);
//
    //      $this->assertResponseStatusCodeSame(400); // Errore 400 per formato errato
    //      $this->assertJsonResponse($client->getResponse(), [
    //          'error' => 'Formato non valido per il primo valore',
    //      ]);
    //  }
//
    // Funzione di utilità per verificare la risposta JSON
    private function assertJsonResponse($response, $expectedData) {
        $data = json_decode($response, true);
        $this->assertEquals($expectedData, $data);
    }
}
