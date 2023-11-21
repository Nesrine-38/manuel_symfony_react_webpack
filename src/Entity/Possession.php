<?php

namespace App\Entity;

use App\Repository\PossessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PossessionRepository::class)]
class Possession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    #[Groups(["possession"])]
    private ?string $nom = null;

    #[ORM\Column]
    #[Groups(["possession"])]
    private ?float $valeur = null;

    #[ORM\Column(length: 40)]
    #[Groups(["possession"])]
    private ?string $type = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'possessions')]
    #[JoinTable(name: "possession_user")]
    #[JoinColumn(name: "possession_id", referencedColumnName: 'id')]
    #[InverseJoinColumn(name: "user_id", referencedColumnName: 'id')]
    #[MaxDepth(1)]
    private Collection $possessions;

    public function __construct()
    {
        $this->possessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getValeur(): ?float
    {
        return $this->valeur;
    }

    public function setValeur(float $valeur): static
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getPossessions(): Collection
    {
        return $this->possessions;
    }

    public function addPossession(User $possession): static
    {
        if (!$this->possessions->contains($possession)) {
            $this->possessions->add($possession);
            
        }

        return $this;
    }

    public function removePossession(User $possession): static
    {
        $this->possessions->removeElement($possession);

        return $this;
    }

    public function addUser(User $user): void
{
    if (!$this->possessions->contains($user)) {
        $this->possessions[] = $user;
    }
}
}
