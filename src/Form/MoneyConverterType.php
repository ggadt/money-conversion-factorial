<?php

namespace App\Form;

use App\Validator\IsValidAmount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class MoneyConverterType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('firstValue', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Assert\Type('string'),
                    new IsValidAmount()
                ]
            ])
            ->add('secondValue', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Assert\Type('string'),
                    new IsValidAmount()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'allow_extra_fields' => false
        ]);
    }
}
