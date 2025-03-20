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
        //TODO: check that the query parameters are not empty, that the structure is the following

        // Assert that value has the following structure:
        // [int](pP)[int](sS)[int](dD)
        $regex = "/([0-9]+)p([0-9]+)s([0-9]+)d/i";

        $firstValueMatches = [];
        $secondValueMatches = [];

        $firstRegexMatch = preg_match($regex,$firstValue, $firstValueMatches);
        $secondRegexMatch = preg_match($regex,$secondValue, $secondValueMatches);

        // Assert that the firstValueMatches and secondValueMatches has 4 values respectively.

        [$echoString, $poundsFirstValue, $shillingsFirstValue, $pencesFirstValue] = $firstValueMatches;
        [$echoString, $poundsSecondValue, $shillingsSecondValue, $pencesSecondValue] = $secondValueMatches;



        return $this->json(
            [
                "firstValueDestructured" => [
                    "Pounds" => $poundsFirstValue,
                    "Shillings" => $shillingsFirstValue,
                    "Pences" => $pencesFirstValue,
                ],
                "secondValueDestructured" => [
                    "Pounds" => $poundsSecondValue,
                    "Shillings" => $shillingsSecondValue,
                    "Pences" => $pencesSecondValue,
                ]
            ]
        );

    }


}
