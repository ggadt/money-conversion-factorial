<?php

declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Form\MoneyConverterType;
use App\Models\Amount;
use App\Service\MoneyConverterService;
use App\Validator\IsValidAmount;
use Exception;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as CustomValidators;

#[OA\Tag(name: 'Old Money System Calculator APIs', description: "These APIs calculates the basic mathematical operations with an old money system.")]
class OldMoneyCalculatorController extends AbstractController {

    /**
     * @throws Exception
     */
    #[Route('/sum', name: 'sum', methods: ['GET'])]
    #[OA\Get(summary: 'Computes the sum between two amounts.')]
    #[OA\Response(
        response: 200,
        description: 'Returns the sum of two amounts',
    )]
    #[OA\Parameter(
        name: 'firstValue',
        description: 'The first value of the amount to be sum. SHOULD respect the format XpYsZd, where X, Y, and Z are integers.',
        in: 'query',
        schema: new OA\Schema(type: 'string', example: "1p6s3d")
    )]
    #[OA\Parameter(
        name: 'secondValue',
        description: 'The second value of the amount to be sum to the first. SHOULD respect the format XpYsZd, where X, Y, and Z are integers.',
        in: 'query',
        schema: new OA\Schema(type: 'string', example: "5p6s4d")
    )]
    public function addition(
        #[MapQueryParameter(filter: \FILTER_VALIDATE_REGEXP, options: ['regexp' => Amount::REGEX_VALIDATION_VALUE])] string $firstValue,
        #[MapQueryParameter(filter: \FILTER_VALIDATE_REGEXP, options: ['regexp' => Amount::REGEX_VALIDATION_VALUE])] string $secondValue,
    ): JsonResponse {
        /*
        //$form = $this->createForm(MoneyConverterType::class);
        //foreach ([$firstValue => "firstValue", $secondValue => "secondValue"] as $var => $str) {
        //    $errors = $validator->validate($var, [
        //        new Assert\NotBlank(),
        //        new Assert\NotNull(),
        //        new CustomValidators\IsValidAmount()
        //    ]);

        //    if (count($errors) > 0) {
        //        return new JsonResponse(["error" => "Error on validation of field $str", "errors" => (string) $errors], 400);
        //    }
        //}

        //$form->submit($request->query->all());
        //if (!$form->isValid())
        //    throw new BadRequestHttpException();
        */
        $firstAmount = Amount::fromString($firstValue);
        $secondAmount = Amount::fromString($secondValue);

        return $this->json([
            "result" => (string) MoneyConverterService::sumAmounts($firstAmount, $secondAmount),
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/subtraction', methods: ['GET'])]
    #[OA\Get(summary: 'Computes the subtraction between two amounts')]
    #[OA\Parameter(
        name: 'firstValue',
        description: 'The first value of the amount for the subtraction. SHOULD respect the format XpYsZd, where X, Y, and Z are integers.',
        in: 'query',
        schema: new OA\Schema(type: 'string', example: "1p6s3d")
    )]
    #[OA\Parameter(
        name: 'secondValue',
        description: 'The second value of the amount to be subtracted to the first. SHOULD respect the format XpYsZd, where X, Y, and Z are integers.',
        in: 'query',
        schema: new OA\Schema(type: 'string', example: "1p6s3d")
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the subtraction of two amounts',
    )]
    public function subtraction(
        #[MapQueryParameter(filter: \FILTER_VALIDATE_REGEXP, options: ['regexp' => Amount::REGEX_VALIDATION_VALUE])] string $firstValue,
        #[MapQueryParameter(filter: \FILTER_VALIDATE_REGEXP, options: ['regexp' => Amount::REGEX_VALIDATION_VALUE])] string $secondValue,
    ): JsonResponse {
        $firstAmount = Amount::fromString($firstValue);
        $secondAmount = Amount::fromString($secondValue);

        if($firstAmount < $secondAmount) {
            throw new BadRequestHttpException('The first amount is less than the second, operation not permitted.
             You cannot subtract money an import of money greater than the first, or you risk debt!');
        }

        return $this->json([
            "result" => (string) MoneyConverterService::subtractAmounts($firstAmount, $secondAmount),
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/multiplication', methods: ['GET'])]
    #[OA\Get(summary: 'Computes the multiplication between an amount and an integer.')]
    #[OA\Parameter(
        name: 'firstValue',
        description: 'The value of the amount to be multiplied with an integer. SHOULD respect the format XpYsZd, where X, Y, and Z are integers.',
        in: 'query',
        schema: new OA\Schema(type: 'string', example: "1p6s3d")
    )]
    #[OA\Parameter(
        name: 'multiplier',
        description: 'An integer that will be multiplied with the amount.',
        in: 'query',
        schema: new OA\Schema(type: 'integer', example: "23")
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the multiplication of two amounts',
    )]
    public function multiplication(
        ValidatorInterface $validator,
        #[MapQueryParameter(filter: \FILTER_VALIDATE_REGEXP, options: ['regexp' => Amount::REGEX_VALIDATION_VALUE])] string $firstValue,
        #[MapQueryParameter] int $multiplier,
    ): JsonResponse {
        $firstAmount = Amount::fromString($firstValue);

        // Asserting that multiplier is eq or greather than 0
        $errors = $validator->validate($multiplier, [
            new Assert\NotBlank(),
            new Assert\NotNull(),
            new Assert\PositiveOrZero()
        ]);

        if (count($errors) > 0) {
            return new JsonResponse(["error" => "Error on validation of multiplier: $multiplier", "errors" => (string) $errors], 400);
        }

        return $this->json([
            "result" => (string) MoneyConverterService::multiplyAmounts($firstAmount, $multiplier),
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/division', methods: ['GET'])]
    #[OA\Get(summary: 'Computes the division between an amount and an integer.')]
    #[OA\Parameter(
        name: 'firstValue',
        description: 'The value of the amount to be divided with an integer. SHOULD respect the format XpYsZd, where X, Y, and Z are integers.',
        in: 'query',
        schema: new OA\Schema(type: 'string', example: "1p6s3d")
    )]
    #[OA\Parameter(
        name: 'divider',
        description: 'An integer that will be divided with the amount.',
        in: 'query',
        schema: new OA\Schema(type: 'integer', example: "5")
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the division of two amounts and the rest of the division.',
    )]
    public function division(
        ValidatorInterface $validator,
        #[MapQueryParameter(filter: \FILTER_VALIDATE_REGEXP, options: ['regexp' => Amount::REGEX_VALIDATION_VALUE])] string $firstValue,
        #[MapQueryParameter] int $divider,
    ): JsonResponse {
        $firstAmount = Amount::fromString($firstValue);

        // Asserting that divider is greather than 0
        $errors = $validator->validate($divider, [
            new Assert\NotBlank(),
            new Assert\NotNull(),
            new Assert\Positive()
        ]);

        if (count($errors) > 0) {
            return new JsonResponse(["error" => "Error on validation of divider: $divider", "errors" => (string) $errors], 400);
        }
        return $this->json([
            "result" => (string) MoneyConverterService::divideAmount($firstAmount, $divider)[0],
            "remainder" =>(string) MoneyConverterService::divideAmount($firstAmount, $divider)[1],
        ]);
    }

}
