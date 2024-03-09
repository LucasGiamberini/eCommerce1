<?php

namespace App\Entity;

use App\Repository\BasketRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BasketRepository::class)]
class Basket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'baskets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $Products = null;

    #[ORM\ManyToOne(inversedBy: 'baskets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Purchase $Purchases = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $Nicotine = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProducts(): ?Product
    {
        return $this->Products;
    }

    public function setProducts(?Product $Products): static
    {
        $this->Products = $Products;

        return $this;
    }

    public function getPurchases(): ?Purchase
    {
        return $this->Purchases;
    }

    public function setPurchases(?Purchase $Purchases): static
    {
        $this->Purchases = $Purchases;

        return $this;
    }

    public function getNicotine(): ?string
    {
        return $this->Nicotine;
    }

    public function setNicotine(?string $Nicotine): static
    {
        $this->Nicotine = $Nicotine;

        return $this;
    }
}
