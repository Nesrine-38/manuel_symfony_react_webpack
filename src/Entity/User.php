<?php

namespace App\Entity;
use App\Entity\Possession;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["user"])]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    #[Groups(["user"])]
    private ?string $nom = null;

    #[ORM\Column(length: 40, nullable: true)]
    #[Groups(["user"])]
    private ?string $prenom = null;

    #[ORM\Column(length: 40)]
    #[Groups(["user"])]
    private ?string $email = null;

    #[ORM\Column(length: 40, nullable: true)]
    #[Groups(["user"])]
    private ?string $adresse = null;

    #[ORM\Column(length: 40)]
    #[Groups(["user"])]
    private ?string $tel = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(["user"])]
    private ?\DateTimeInterface $bithDate = null;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getBithDate(): ?\DateTimeInterface
    {
        return $this->bithDate;
    }

    public function setBithDate(\DateTimeInterface $bithDate): static
    {
        $this->bithDate = $bithDate;

        return $this;
    }
    private int $age;

    #[ORM\ManyToMany(targetEntity: Possession::class, mappedBy: 'possessions', cascade: ["persist"])]
    #[JoinTable(name: "possession_user")]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name: "possession_id", referencedColumnName: 'id')]
    #[MaxDepth(1)]
    private Collection $possessions;

    public function __construct()
    {
        $this->possessions = new ArrayCollection();
    }
 
    /**
     * @param \DateTimeInterface $birthDate
     * @return int
     * 
     */
    #[Groups(["user"])]
    public function getAge(): int
    {
 
        //Calculer l'age de chaque user
        $datetime1 = new \datetime('now'); // date actuelle
        $datetime2 = $this->getBithDate();
        $age = $datetime1->diff($datetime2, true)->y; // le y = nombre d'années 
        
        
        return $age;
    }

    /**
     * @return Collection<int, Possession>
     */
    public function getPossessions(): Collection
    {
        return $this->possessions;
    }

 /**
 * @param Possession $possession
 */
public function addPossession(Possession $possession): void
{
    if (!$this->possessions->contains($possession)) {
        $this->possessions[] = $possession;
        $possession->addUser($this); 
    }
}

    public function removePossession(Possession $possession): static
    {
        if ($this->possessions->removeElement($possession)) {
            $possession->removePossession($this);
        }

        return $this;
    }
}
