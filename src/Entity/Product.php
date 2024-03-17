<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
//#[ORM\Table(name="Product", indexes={@ORM\Index(columns{"name"}), } )]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $detail = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[ORM\OneToMany(targetEntity: Picture::class, mappedBy: 'Products' , orphanRemoval: true )]
    private Collection $pictures;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Aroma $Aroma = null;

 

    #[ORM\OneToMany(targetEntity: Basket::class, mappedBy: 'Products')]
    private Collection $baskets;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'Favorite')]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: Nicotine::class, inversedBy: 'products')]
    private Collection $Nicotines;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Height $Capacity = null;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->capacity = new ArrayCollection();
        $this->baskets = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->Nicotines = new ArrayCollection();
    }

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

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): static
    {
        $this->detail = $detail;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): static
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
            $picture->setProducts($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): static
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getProducts() === $this) {
                $picture->setProducts(null);
            }
        }

        return $this;
    }

    public function getAroma(): ?Aroma
    {
        return $this->Aroma;
    }

    public function setAroma(?Aroma $Aroma): static
    {
        $this->Aroma = $Aroma;

        return $this;
    }
//one-to-many
 //   public function getNicotine(): ?Nicotine
 //   {
  //      return $this->Nicotine;
  //  }

 //   public function setNicotine(?Nicotine $Nicotine): static
  //  {
 //       $this->Nicotine = $Nicotine;

//        return $this;
 //   }






 

    /**
     * @return Collection<int, Basket>
     */
    public function getBaskets(): Collection
    {
        return $this->baskets;
    }

    public function addBasket(Basket $basket): static
    {
        if (!$this->baskets->contains($basket)) {
            $this->baskets->add($basket);
            $basket->setProducts($this);
        }

        return $this;
    }

    public function removeBasket(Basket $basket): static
    {
        if ($this->baskets->removeElement($basket)) {
            // set the owning side to null (unless already changed)
            if ($basket->getProducts() === $this) {
                $basket->setProducts(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addFavorite($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeFavorite($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Nicotine>
     */
    public function getNicotines(): Collection
    {
        return $this->Nicotines;
    }

    public function addNicotine(Nicotine $nicotine): static
    {
        if (!$this->Nicotines->contains($nicotine)) {
            $this->Nicotines->add($nicotine);
        }

        return $this;
    }

    public function removeNicotine(Nicotine $nicotine): static
    {
        $this->Nicotines->removeElement($nicotine);

        return $this;
    }

    public function getCapacity(): ?Height
    {
        return $this->Capacity;
    }

    public function setCapacity(?Height $Capacity): static
    {
        $this->Capacity = $Capacity;

        return $this;
    }
}
