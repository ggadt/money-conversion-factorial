<?php

declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Controller\BaseApiController;
use App\Entity\Product;
use App\Form\ProductType;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/products')]
#[OA\Tag(name: 'Products API', description: "These APIs serves a series of products examples. Basic CRUD operations are allowed.")]
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
    #[OA\Get(summary: 'Retrieves all the products availables.')]
    #[OA\Response(
        response: 200,
        description: 'A list of all products is retrieved successfully.',
        content: new OA\JsonContent(
            example: '[{"id": 1, "name": "John Doe", "price": 10.45},{"id": 2, "name": "John Doe", "price": 10.45} ]'
        )
    )]
    public function index(): Response {
        return $this->json($this->productRepository->findAll());
    }

    #[Route('/{product<\d+>}', methods: ['GET'])]
    #[OA\Get(summary: 'Retrieves a specific product, given an existing product with a valid id.')]
    #[OA\Parameter(
        name: 'product',
        description: 'The ID of the product to retrieve.',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer', example: "1")
    )]
    #[OA\Response(
        response: 200,
        description: 'If a product with the given ID exists, it is returned.',
        content: new OA\JsonContent(
            example: '{"id": 1, "name": "John Doe", "price": 10.45}'
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'If there are no products with the given ID, an error is thrown.',
    )]
    public function findById(Product $product): Response {
        return $this->json($product);
    }

    #[Route('', methods: ['POST'])]
    #[OA\Post(summary: 'Insert a product into the database.', description: 'This request, given valid parameters, insert a new product into the database.')]
    #[OA\Parameter(
        name: 'name',
        description: 'Name of the product to insert',
        in: 'query',
        required: true,
        schema: new OA\Schema(type: 'string', example: "Product 1")
    )]
    #[OA\Parameter(
        name: 'price',
        description: 'Price of the product to insert',
        in: 'query',
        required: true,
        schema: new OA\Schema(type: 'number', example: "10.00")
    )]
    #[OA\Response(
        response: 201,
        description: 'If the parameters are fulfilled correctly, it is returned a successful response with the object.',
        content: new OA\JsonContent(
            example: '{"response":"string", "product":{"id": 1, "name": "John Doe", "price": 10.45}}'
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'If some parameters are missing, an error is thrown.',
    )]
    public function create(Request $request): Response {
        $form = $this->createForm(ProductType::class);
        $form->submit($request->query->all());
        if (!$form->isValid()) {
            return $this->json([
                "response" => "Fields are not filled correctly.", "errors" => (string) $form->getErrors(true)
            ],
                Response::HTTP_BAD_REQUEST);
        }

        $product = new Product();
        $product->setName($request->get('name'));
        $price = floatval($request->get('price'));
        $product->setPrice($price);

        $this->productRepository->save($product, true);
        return $this->json([
            "response" => "Product added successfully!",
            "product" => $product
        ], Response::HTTP_CREATED);
    }

    #[Route('/{product<\d+>}', methods: ['PATCH'])]
    #[OA\Patch(summary: 'Update an existent product into the database.', description: 'This request, given valid id product already existent, updates attributes of the product into the database.')]
    #[OA\Parameter(
        name: 'product',
        description: 'The ID of the product to update.',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer', example: "1"),
    )]
    #[OA\Parameter(
        name: 'name',
        description: 'The name of the product to update.',
        in: 'path',
        schema: new OA\Schema(type: 'string', example: "New Product")
    )]
    #[OA\Parameter(
        name: 'price',
        description: 'The price of the product to update.',
        in: 'path',
        schema: new OA\Schema(type: 'number', example: "100.00")
    )]
    #[OA\Response(
        response: 200,
        description: 'If a product with the given ID exists, it is updated.',
        content: new OA\JsonContent(
            example: '{"id": 1, "name": "John Doe", "price": 10.45}'
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'If there are no products with the given ID, an error is thrown.',
    )]
    public function update(Request $request, Product $product): Response {

        $name = $request->get('name');
        if ($name) {
            $product->setName($name);
        }

        $price = $request->get('price');
        if ($price) {
            $product->setPrice(floatval($price));
        }

        $this->productRepository->save($product, true);
        $response = ["response" => "Product updated successfully", "product" => $product];
        return $this->json($response);
    }

    #[Route('/{product<\d+>}', methods: ['DELETE'])]
    #[OA\Delete(summary: 'Delete an existent product into the database.', description: 'This request, given valid id product already existent, delete permanently a product into the database.')]
    #[OA\Parameter(
        name: 'product',
        description: 'The ID of the product to delete.',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer', example: "1"),
    )]
    #[OA\Response(
        response: 200,
        description: 'If a product with the given ID exists, it is deleted.',
        content: new OA\JsonContent(
            example: '{"response": "string"}'
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'If there are no products with the given ID, an error is thrown.',
    )]
    public function delete(Request $request, Product $product): Response {
        $this->productRepository->delete($product, true);
        $response = ["response" => "Product deleted successfully"];
        return $this->json($response);
    }
}
