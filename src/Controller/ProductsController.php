<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
class ProductsController extends AbstractController
{
    #[Route('/products')]
    public function index(Request $request): Response
    {
        return $this->json($this->getDoctrine()->getRepository(Product::class)->findAll());
    }
}
