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

#[OA\Tag(name: 'Old Money System Calculator APIs')]
class OldMoneyCalculatorController extends AbstractController {

    /**
     * @throws Exception
     */
    #[Route('/sum', name: 'sum', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the sum of two amounts',
    )]
    #[OA\Parameter(
        name: 'firstValue',
        description: 'The first value of the amount to be sum',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'secondValue',
        description: 'The second value of the amount to be sum to the first',
        in: 'query',
        schema: new OA\Schema(type: 'string')
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
    public function multiplication(
        #[MapQueryParameter(filter: \FILTER_VALIDATE_REGEXP, options: ['regexp' => Amount::REGEX_VALIDATION_VALUE])] string $firstValue,
        #[MapQueryParameter(filter: \FILTER_VALIDATE_INT, options: ['min_range' => 0])] int $multiplier,
    ): JsonResponse {
        $firstAmount = Amount::fromString($firstValue);

        return $this->json([
            "result" => (string) MoneyConverterService::multiplyAmounts($firstAmount, $multiplier),
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/division', methods: ['GET'])]
    public function division(
        #[MapQueryParameter(filter: \FILTER_VALIDATE_REGEXP, options: ['regexp' => Amount::REGEX_VALIDATION_VALUE])] string $firstValue,
        #[MapQueryParameter(filter: \FILTER_VALIDATE_INT, options: ['min_range' => 0])] int $divider,
    ): JsonResponse {
        $firstAmount = Amount::fromString($firstValue);

        return $this->json([
            "result" => (string) MoneyConverterService::divideAmount($firstAmount, $divider)[0],
            "remainder" =>(string) MoneyConverterService::divideAmount($firstAmount, $divider)[1],
        ]);
    }

}
