<?php

namespace App\Entity;

use App\Repository\VenteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VenteRepository::class)]
class Vente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, VenteProduit>
     */
    #[ORM\OneToMany(targetEntity: VenteProduit::class, mappedBy: 'vente')]
    private Collection $venteProduits;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function __construct()
    {
        $this->venteProduits = new ArrayCollection();
        $this->date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, VenteProduit>
     */
    public function getVenteProduits(): Collection
    {
        return $this->venteProduits;
    }

    public function addVenteProduit(VenteProduit $venteProduit): static
    {
        if (!$this->venteProduits->contains($venteProduit)) {
            $this->venteProduits->add($venteProduit);
            $venteProduit->setVente($this);
        }

        return $this;
    }

    public function removeVenteProduit(VenteProduit $venteProduit): static
    {
        if ($this->venteProduits->removeElement($venteProduit)) {
            // set the owning side to null (unless already changed)
            if ($venteProduit->getVente() === $this) {
                $venteProduit->setVente(null);
            }
        }

        return $this;
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
}
