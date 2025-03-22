<?php

declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Controller\BaseApiController;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/products')]
class ProductsApiController extends BaseApiController
{
    private EntityManagerInterface $entityManager;
    private ProductRepository $productRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        $this->entityManager = $entityManager;
        $this->productRepository = $this->entityManager->getRepository(Product::class);
    }

    #[Route('', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->json($this->entityManager->getRepository(Product::class)->findAll());
    }

    #[Route('/{productId}', methods: ['GET', 'PATCH', 'DELETE'])]
    public function findById(Request $request, $productId): Response
    {
        if (!$productId) {
            return $this->json(['message' => 'You should specify a valid id'], Response::HTTP_BAD_REQUEST);
        }

        $product = $this->productRepository->findOneBy(['id' => $productId]);
        if (!$product) {
            throw new NotFoundHttpException();
        }
        $response = $product;
        if ($request->getMethod() == 'PATCH') {

            $title = $request->get('title');
            if($title) $product->setName($title);

            $price = $request->get('price');
            if($price) {
                $price = floatval($price);
                $product->setPrice($price);
            }

            $this->entityManager->persist($product);
            $this->entityManager->flush();
            $response = ["response" => "Product updated successfully", "product" => $product];
        } else if ($request->getMethod() == 'DELETE') {
            $this->entityManager->remove($product);

            $this->entityManager->flush();
            $response = ["response" => "Product deleted successfully"];
        }

        return $this->json($response);
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
        return $this->json([
            "response" => "Product added successfully!",
            "product" => $product
        ]);
    }
}
