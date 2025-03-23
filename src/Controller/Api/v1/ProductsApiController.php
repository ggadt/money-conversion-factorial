<?php

declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Controller\BaseApiController;
use App\Entity\Product;
use OpenApi\Attributes as OA;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/products')]
#[OA\Tag(name: 'Products API')]
class ProductsApiController extends BaseApiController {
    private EntityManagerInterface $entityManager;
    private ProductRepository $productRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        $this->entityManager = $entityManager;
        $this->productRepository = $this->entityManager->getRepository(Product::class);
    }

    #[Route('', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: [new \Nelmio\ApiDocBundle\Attribute\Model(type: Product::class)]
    )]
    public function index(Request $request): Response
    {
        return $this->json($this->productRepository->findAll());
    }

    #[Route('/{product<\d+>}', methods: ['GET'])]
    public function findById(Request $request, Product $product): Response {
        return $this->json($product);
    }

    #[Route('', methods: ['POST'])]
    #[OA\Response(
        response: 201,
        description: 'Successful response',
        content: new \Nelmio\ApiDocBundle\Attribute\Model(type: Product::class)
    )]
    public function create(Request $request): Response
    {
        $product = new Product();
        $product->setName($request->get('name'));
        $price = floatval($request->get('price'));
        $product->setPrice($price);

        $this->productRepository->save($product);
        return $this->json([
            "response" => "Product added successfully!",
            "product" => $product
        ], Response::HTTP_CREATED);
    }

    #[Route('/{product<\d+>}', methods: ['PATCH'])]
    public function update(Request $request, Product $product): Response {

        $title = $request->get('title');
        if($title) $product->setName($title);

        $price = $request->get('price');
        if($price) {
            $product->setPrice(floatval($price));
        }

        $this->productRepository->save($product, true);
        $response = ["response" => "Product updated successfully", "product" => $product];
        return $this->json($response);
    }

    #[Route('/{product<\d+>}', methods: ['DELETE'])]
    public function delete(Request $request, Product $product): Response {
        $this->productRepository->delete($product, true);
        $response = ["response" => "Product deleted successfully"];
        return $this->json($response);
    }
}
