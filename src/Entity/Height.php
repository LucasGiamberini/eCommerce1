<?php

namespace App\Entity;

use App\Repository\HeightRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HeightRepository::class)]
class Height
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $milliliter = null;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'Capacity')]
    private Collection $products;


    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMilliliter(): ?string
    {
        return $this->milliliter;
    }

    public function setMilliliter(string $milliliter): static
    {
        $this->milliliter = $milliliter;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCapacity($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCapacity() === $this) {
                $product->setCapacity(null);
            }
        }

        return $this;
    }

   
}
