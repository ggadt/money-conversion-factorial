<?php

namespace temp;


use PHPUnit\Framework\TestCase;

class ProductApiTest extends TestCase {

    protected $baseUrl = '/api/v1/products';
    protected $client;

    function __construct() {
        $this->client = new \GuzzleHttp\Client([
            'base_url' => '/api/v1/products',
            'defaults' => [
                'exceptions' => false
            ]
        ]);
    }

    public function testCreateProduct() {
        $client = $this->client;

        // Creazione di un nuovo articolo
        $client->request('POST', $this->baseUrl.'/create', [
            'name' => 'Smartphone',
            'price' => '10.00',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonResponse($client->getResponse(), [
            'message' => 'Articolo creato con successo',
        ]);
    }

    public function testCreateProductWithMissingData() {
        $client = $this->client;

        // Inserimento articolo senza nome
        $client->request('POST', $this->baseUrl, [
            'price' => '5',
        ]);

        $this->assertResponseStatusCodeSame(400); // Errore 400 per dati mancanti
        $this->assertJsonResponse($client->getResponse(), [
            'error' => 'Nome dell\'articolo mancante',
        ]);
    }

    // Test: Inserimento Articolo con formato prezzo non valido
    public function testCreateProductWithInvalidPriceFormat()
    {
        $client = $this->client;

        // Inserimento articolo con formato prezzo non valido
        $client->request('POST', $this->baseUrl, [
            'name' => 'Phone',
            'price' => 'test', // formato del prezzo errato
        ]);

        $this->assertResponseStatusCodeSame(400); // Errore 400 per formato prezzo errato
        $this->assertJsonResponse($client->getResponse(), [
            'error' => 'Formato del prezzo non valido',
        ]);
    }

    public function testGetProduct() {
        $client = $this->client;

        // Recupero di un articolo esistente
        $client->request('GET', $this->baseUrl.'/1'); // Assumiamo che l'articolo con ID 1 esista

        $this->assertResponseIsSuccessful();
        $this->assertJsonResponse($client->getResponse(), [
            'id' => 1,
            'name' => 'Smartphone',
            'price' => '1p 10s 5d',
        ]);
    }

    public function testUpdateProduct() {
        $client = $this->client;

        // Aggiornamento di un articolo esistente
        $client->request('PATCH', $this->baseUrl.'/1', [
            'name' => 'Smartphone Pro',
            'price' => '5',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonResponse($client->getResponse(), [
            'message' => 'Articolo aggiornato con successo',
        ]);
    }

    public function testDeleteProduct() {
        $client = $this->client;

        // Rimozione di un articolo esistente
        $client->request('DELETE', $this->baseUrl.'/1');

        $this->assertResponseIsSuccessful();
        $this->assertJsonResponse($client->getResponse(), [
            'message' => 'Articolo rimosso con successo',
        ]);
    }

    public function testGetNonExistentProduct() {
        $client = $this->client;

        // Tentativo di recuperare un articolo con un ID inesistente
        $client->request('GET', $this->baseUrl.'/555'); // ID che non esiste

        $this->assertResponseStatusCodeSame(404); // Errore 404 Not Found
        $this->assertJsonResponse($client->getResponse(), [
            'error' => 'Articolo non trovato',
        ]);
    }

    public function testUpdateNonExistentProduct() {
        $client = $this->client;

        // Tentativo di aggiornare un articolo con un ID inesistente
        $client->request('PATCH', $this->baseUrl.'/999', [
            'name' => 'Non-Existent Product',
            'price' => '5',
        ]);

        $this->assertResponseStatusCodeSame(404);
        $this->assertJsonResponse($client->getResponse(), [
            'error' => 'Articolo non trovato',
        ]);
    }

    private function assertJsonResponse($response, $expectedData) {
        $data = json_decode($response->getContent(), true);
        $this->assertEquals($expectedData, $data);
    }
}
