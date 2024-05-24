<?php

namespace App\Entity;

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
     * @var Collection<int, Commande>
     */
    #[ORM\ManyToMany(targetEntity: Commande::class, mappedBy: 'Produits')]
    private Collection $commandes;

    /**
     * @var Collection<int, CommandeProduit>
     */
    #[ORM\OneToMany(targetEntity: CommandeProduit::class, mappedBy: 'Produit')]
    private Collection $produit;

    /**
     * @var Collection<int, VenteProduit>
     */
    #[ORM\OneToMany(targetEntity: VenteProduit::class, mappedBy: 'produit')]
    private Collection $venteProduits;

    public function __construct()
    {
        $this->stock = 0;
        $this->tva = false;
        $this->Fournisseur = new ArrayCollection();
        $this->detailcommandes = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->produit = new ArrayCollection();
        $this->venteProduits = new ArrayCollection();
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
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->addProduit($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            $commande->removeProduit($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandeProduit>
     */
    public function getProduit(): Collection
    {
        return $this->produit;
    }

    public function addProduit(CommandeProduit $produit): static
    {
        if (!$this->produit->contains($produit)) {
            $this->produit->add($produit);
            $produit->setProduit($this);
        }

        return $this;
    }

    public function removeProduit(CommandeProduit $produit): static
    {
        if ($this->produit->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getProduit() === $this) {
                $produit->setProduit(null);
            }
        }

        return $this;
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
            $venteProduit->setProduit($this);
        }

        return $this;
    }

    public function removeVenteProduit(VenteProduit $venteProduit): static
    {
        if ($this->venteProduits->removeElement($venteProduit)) {
            // set the owning side to null (unless already changed)
            if ($venteProduit->getProduit() === $this) {
                $venteProduit->setProduit(null);
            }
        }

        return $this;
    }
}
