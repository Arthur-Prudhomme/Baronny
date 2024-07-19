<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use DateTime;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    forceEager:false,
    operations: [
        new GetCollection(security: "is_granted('ROLE_BARMAN')", securityMessage: 'Unauthorized'),
        new Post(security: "is_granted('ROLE_SERVEUR')", securityMessage: 'Unauthorized'),
        new Get(security: "is_granted('ROLE_BARMAN') or is_granted('ROLE_SERVEUR')", securityMessage: 'Unauthorized'),
        new Patch(security: "is_granted('ROLE_SERVEUR') or is_granted('ROLE_SERVEUR')", securityMessage: 'Unauthorized'),
        new Put(security: "(is_granted('ROLE_SERVEUR') or is_granted('ROLE_SERVEUR')) and object.getStatus() != 'payée'", securityMessage: 'Unauthorized'),
],)]
#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;

    #[Groups('read')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    /**
     * @var Collection<int, Drink>
     */
    
    #[Groups(['read', 'write'])]
    #[ORM\ManyToMany(targetEntity: Drink::class, inversedBy: 'orders')]
    private Collection $drinks;

    #[ORM\Column]
    #[Groups(['read', 'write'])]
    private ?int $tableNumber = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[Groups(['read', 'write'])]
    private ?User $waiter = null;

    #[ORM\ManyToOne(inversedBy: 'ordersBarman')]
    #[Groups(['read', 'write'])]
    private ?User $barman = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $status = null;

    public function __construct()
    {
        $this->drinks = new ArrayCollection();
        $this->date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, Drink>
     */
    public function getDrinks(): Collection
    {
        return $this->drinks;
    }

    public function addDrink(Drink $drink): static
    {
        if (!$this->drinks->contains($drink)) {
            $this->drinks->add($drink);
        }

        return $this;
    }

    public function removeDrink(Drink $drink): static
    {
        $this->drinks->removeElement($drink);

        return $this;
    }

    public function getTableNumber(): ?int
    {
        return $this->tableNumber;
    }

    public function setTableNumber(int $tableNumber): static
    {
        $this->tableNumber = $tableNumber;

        return $this;
    }

    public function getWaiter(): ?User
    {
        return $this->waiter;
    }

    public function setWaiter(?User $waiter): static
    {
        $this->waiter = $waiter;

        return $this;
    }

    public function getBarman(): ?User
    {
        return $this->barman;
    }

    public function setBarman(?User $barman): static
    {
        $this->barman = $barman;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
