<?php

namespace App\Validator;

use App\Models\Amount;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class IsValidAmountValidator extends ConstraintValidator {
    public function validate(mixed $value, Constraint $constraint): void {

        /* @var IsValidAmount $constraint */
        if (null === $value || '' === $value) {
            $this->context->buildViolation("The parameter should not be blank.")
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }

        if (preg_match(Amount::REGEX_VALIDATION_VALUE, $value)) return;

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
