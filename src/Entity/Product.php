<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: "products")]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[OA\Property(description: 'The unique identifier of the product.')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[OA\Property(description: 'The name of the product.')]
    private ?string $name = null;

    #[ORM\Column]
    #[OA\Property(description: 'The price of the product.')]
    private ?float $price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name . " - (price: $this->price $)";
    }
}
