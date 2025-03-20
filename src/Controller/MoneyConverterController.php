<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class MoneyConverterController extends AbstractController {
    #[Route('/sum')]
    public function addition(
        #[MapQueryParameter] string $firstValue,
        #[MapQueryParameter] string $secondValue,
    ): JsonResponse {
        return $this->json(["firstValue" => $firstValue, "secondValue" => $secondValue]);
    }
}
