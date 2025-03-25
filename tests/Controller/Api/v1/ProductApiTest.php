<?php

namespace App\Tests\Controller\Api\v1;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductApiTest extends TestCase {

    protected $baseUrl = '/api/v1/products';
    protected $client;

    function setUp(): void {
        $this->client = new Client([
            'base_uri' => 'http://nginx',
            'defaults' => [
                'exceptions' => true
            ]
        ]);
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function creatingBaseProduct(): array {
        // Create base product
        $response = $this->client->post($this->baseUrl, [
            "query" => [
                'name' => 'Smartphone',
                'price' => '10.00',
            ]
        ]);
        $responseDecodified = json_decode($response->getBody()->getContents(), true);
        return $responseDecodified['product'];
    }

    protected function tearDown(): void {
        $this->client = null;
    }

    /**
     * @throws GuzzleException
     */
    public function testCreateProduct() {
        // Creazione di un nuovo articolo
        $response = $this->client->post($this->baseUrl, [
            "query" => [
                'name' => 'Smartphone',
                'price' => '10.00',
            ]
        ]);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $responseDecodified = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('response', $responseDecodified);
        $this->assertArrayHasKey('product', $responseDecodified);
    }

    public function testCreateProductWithMissingData() {

        try {
            // Inserimento articolo senza nome
            $this->client->post($this->baseUrl, [
                'price' => '5',
            ]);
        } catch (GuzzleException $e) {
            $response = $e->getResponse();
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
            $responseDecodified = json_decode($response->getBody()->getContents(), true);
            $this->assertArrayHasKey("response", $responseDecodified);
            $this->assertArrayHasKey("errors", $responseDecodified);
        }
    }

    // Test: Inserimento Articolo con formato prezzo non valido
    public function testCreateProductWithInvalidPriceFormat() {
        try {
            // Inserimento articolo senza nome
            $this->client->post($this->baseUrl, [
                'price' => 'test',
            ]);
        } catch (GuzzleException $e) {
            $response = $e->getResponse();
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
            $responseDecodified = json_decode($response->getBody()->getContents(), true);
            $this->assertArrayHasKey("response", $responseDecodified);
            $this->assertArrayHasKey("errors", $responseDecodified);
        }
    }

    /**
     * @throws GuzzleException
     */
    public function testGetProduct() {
        $product = $this->creatingBaseProduct();

        // Recupero dell'articolo appena creato
        $response = $this->client->get($this->baseUrl.'/'.$product['id']); // Assumiamo che l'articolo con ID 1 esista
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $responseDecodified = json_decode($response->getBody()->getContents(), true);
        $this->assertEquals("Smartphone", $responseDecodified['name']);
        $this->assertEquals(10, $responseDecodified['price']);
    }

    public function testUpdateProduct() {

        $product = $this->creatingBaseProduct();

        // Aggiornamento di un articolo esistente
        $response = $this->client->patch($this->baseUrl.'/'.$product['id'], [
            "query" => [
                'name' => 'Smartphone Pro',
                'price' => '5.99',
            ]
        ]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $responseDecodified = json_decode($response->getBody()->getContents(), true);
        $this->assertEquals("Smartphone Pro", $responseDecodified['product']['name']);
        $this->assertEquals(5.99, $responseDecodified['product']['price']);
    }

    public function testDeleteProduct() {
        $product = $this->creatingBaseProduct();
        $response = $this->client->delete($this->baseUrl.'/'.$product['id']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $responseDecodified = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('response', $responseDecodified);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetNonExistentProduct() {
        try {
            $this->client->get($this->baseUrl.'/555'); // ID non esistente
        } catch (GuzzleException $e) {
            $response = $e->getResponse();
            $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        }
    }

    public function testUpdateNonExistentProduct() {
        try {
            $this->client->patch($this->baseUrl.'/555', [
                "query" => [
                    'name' => 'Non-Existent Product',
                    'price' => '5',
                ]
            ]); // ID non esistente
        } catch (GuzzleException $e) {
            $response = $e->getResponse();
            $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        }
    }

    private function assertJsonResponse($response, $expectedData) {
        $data = json_decode($response, true);
        $this->assertEquals($expectedData, $data);
    }
}
