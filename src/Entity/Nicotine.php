<?php

namespace App\Entity;

use App\Repository\NicotineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NicotineRepository::class)]
class Nicotine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

  

  //  #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'Nicotine')]
 //   private Collection $products;

    #[ORM\Column(length: 255)]
    private ?string $proportioning = null;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'Nicotines')]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }



 //   /**
 //    * @return Collection<int, Product>
 //    */
 //   public function getProducts(): Collection
 //   {
 //       return $this->products;
 //  }

 //   public function addProduct(Product $product): static
  //  {
 //       if (!$this->products->contains($product)) {
 //           $this->products->add($product);
//            $product->setNicotine($this);
 //       }

  //      return $this;
  //  }

 //   public function removeProduct(Product $product): static
 //   {
  //      if ($this->products->removeElement($product)) {
 //           // set the owning side to null (unless already changed)
 //           if ($product->getNicotine() === $this) {
 //               $product->setNicotine(null);
   //         }
  //      }

  //      return $this;
 //   }

    public function getProportioning(): ?string
    {
        return $this->proportioning;
    }

    public function setProportioning(string $proportioning): static
    {
        $this->proportioning = $proportioning;

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
            $product->addNicotine($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            $product->removeNicotine($this);
        }

        return $this;
    }
}
