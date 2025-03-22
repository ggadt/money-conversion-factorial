<?php

declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Controller\BaseApiController;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/products')]
class ProductsApiController extends BaseApiController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->json($this->entityManager->getRepository(Product::class)->findAll());
    }

    #[Route('/{id}', methods: ['GET'])]
    public function findById(Request $request): Response
    {
        $productId = $request->attributes->get('id');

        if(!$productId) {
            return $this->json(['message' => 'You should specify a valid id'], Response::HTTP_BAD_REQUEST);
        }
        return $this->json($this->entityManager->getRepository(Product::class)->findOneBy(['id' => $productId]));
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $product = new Product();
        $product->setName($request->get('name'));
        $price = floatval($request->get('price'));
        $product->setPrice($price);

        $this->entityManager->persist($product);
        $this->entityManager->flush();
        return $this->json(["response" => "Product added successfully! Product: " . $product]);
    }
}
