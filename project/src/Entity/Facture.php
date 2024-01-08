<?php

// src/Entity/Facture.php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)
 * @ORM\Table(name="facture")
 */
class Facture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $anneeFacturation = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?float $numeroFacture = null;

    /**
     * @ORM\ManyToOne(targetEntity=Fournisseur::class, inversedBy="factures")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Fournisseur $fournisseur = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $nomEntrepriseVendeur = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $adresseEntrepriseVendeur = null;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private ?string $telephoneEntrepriseVendeur = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $nomClientAcheteur = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $adresseClientAcheteur = null;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private ?string $telephoneClientAcheteur = null;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $sousTotal = null;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $tva = null;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $total = null;


    // ... (getters and setters)


    public function getId(): ?int
    {
        return $this->id;
    }

   
    /**
     * Get the value of anneeFacturation
     */
    public function getAnneeFacturation(): ?int
    {
        return $this->anneeFacturation;
    }

    /**
     * Set the value of anneeFacturation
     *
     * @return self
     */
    public function setAnneeFacturation(?int $anneeFacturation): self
    {
        $this->anneeFacturation = $anneeFacturation;
        return $this;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): static
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }
    public function getSousTotal(): ?float
    {
        return $this->sousTotal;
    }

    public function setSousTotal(?float $sousTotal): self
    {
        $this->sousTotal = $sousTotal;
        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(?float $tva): self
    {
        $this->tva = $tva;
        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(?float $total): self
    {
        $this->total = $total;
        return $this;
    }
    public function getNumeroFacture(): ?float
    {
        return $this->numeroFacture;
    }

    /**
     * Set the value of numeroFacture
     *
     * @return self
     */
    public function setNumeroFacture(?float $numeroFacture): self
    {
        $this->numeroFacture = $numeroFacture;
        return $this;
    }
    /**
     * Get the value of nomClientAcheteur
     */
    public function getNomClientAcheteur(): ?string
    {
        return $this->nomClientAcheteur;
    }

    /**
     * Set the value of nomClientAcheteur
     *
     * @return self
     */
    public function setNomClientAcheteur(?string $nomClientAcheteur): self
    {
        $this->nomClientAcheteur = $nomClientAcheteur;
        return $this;
    }
    public function getAdresseClientAcheteur(): ?string
    {
        return $this->adresseClientAcheteur;
    }

    /**
     * Set the value of adresseClientAcheteur
     *
     * @return self
     */
    public function setAdresseClientAcheteur(?string $adresseClientAcheteur): self
    {
        $this->adresseClientAcheteur = $adresseClientAcheteur;
        return $this;
    }
    public function getTelephoneClientAcheteur(): ?string
    {
        return $this->telephoneClientAcheteur;
    }

    /**
     * Set the value of telephoneClientAcheteur
     *
     * @return self
     */
    public function setTelephoneClientAcheteur(?string $telephoneClientAcheteur): self
    {
        $this->telephoneClientAcheteur = $telephoneClientAcheteur;
        return $this;
    }
}
