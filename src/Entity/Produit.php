<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(length: 255)]
    private ?string $designation = null;

    #[ORM\Column]
    private ?int $prix = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $lot = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $peremption = null;

    #[ORM\Column(nullable: true)]
    private ?int $prixpublic = null;

    #[ORM\Column]
    private ?int $stock = null;
//
//    #[ORM\Column(type: Types::DATE_MUTABLE)]
//    private ?\DateTimeInterface $creation = null;

    #[ORM\Column]
    private ?bool $tva = null;

    /**
     * @var Collection<int, Fournisseur>
     */
    #[ORM\ManyToMany(targetEntity: Fournisseur::class, inversedBy: 'produits')]
    private Collection $Fournisseur;

    /**
     * @var Collection<int, Detailcommande>
     */
    #[ORM\OneToMany(targetEntity: Detailcommande::class, mappedBy: 'relation')]
    private Collection $detailcommandes;

    public function __construct()
    {
        $this->stock = 0;
        $this->tva = false;
        $this->Fournisseur = new ArrayCollection();
        $this->detailcommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): static
    {
        $this->designation = $designation;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLot(): ?int
    {
        return $this->lot;
    }

    public function setLot(int $lot): static
    {
        $this->lot = $lot;

        return $this;
    }

    public function getPeremption(): ?\DateTimeInterface
    {
        return $this->peremption;
    }

    public function setPeremption(\DateTimeInterface $peremption): static
    {
        $this->peremption = $peremption;

        return $this;
    }

    public function getPrixpublic(): ?int
    {
        return $this->prixpublic;
    }

    public function setPrixpublic(int $prixpublic): static
    {
        $this->prixpublic = $prixpublic;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

//    public function getCreation(): ?\DateTimeInterface
//    {
//        return $this->creation;
//    }
//
//    public function setCreation(\DateTimeInterface $creation): static
//    {
//        $this->creation = $creation;
//
//        return $this;
//    }


    public function isTva(): ?bool
    {
        return $this->tva;
    }

    public function setTva(bool $tva): static
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * @return Collection<int, Fournisseur>
     */
    public function getFournisseur(): Collection
    {
        return $this->Fournisseur;
    }

    public function addFournisseur(Fournisseur $fournisseur): static
    {
        if (!$this->Fournisseur->contains($fournisseur)) {
            $this->Fournisseur->add($fournisseur);
        }

        return $this;
    }

    public function removeFournisseur(Fournisseur $fournisseur): static
    {
        $this->Fournisseur->removeElement($fournisseur);

        return $this;
    }


    /**
     * @return Collection<int, Detailcommande>
     */
    public function getDetailcommandes(): Collection
    {
        return $this->detailcommandes;
    }

    public function addDetailcommande(Detailcommande $detailcommande): static
    {
        if (!$this->detailcommandes->contains($detailcommande)) {
            $this->detailcommandes->add($detailcommande);
            $detailcommande->setRelation($this);
        }

        return $this;
    }

    public function removeDetailcommande(Detailcommande $detailcommande): static
    {
        if ($this->detailcommandes->removeElement($detailcommande)) {
            // set the owning side to null (unless already changed)
            if ($detailcommande->getRelation() === $this) {
                $detailcommande->setRelation(null);
            }
        }

        return $this;
    }
}
