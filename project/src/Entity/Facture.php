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
    private ?int $contrat = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $client = null;

    /**
     * @ORM\ManyToOne(targetEntity=Fournisseur::class, inversedBy="factures")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Fournisseur $fournisseur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContrat(): ?int
    {
        return $this->contrat;
    }

    public function setContrat(?int $contrat): static
    {
        $this->contrat = $contrat;

        return $this;
    }

    public function getClient(): ?int
    {
        return $this->client;
    }

    public function setClient(?int $client): static
    {
        $this->client = $client;

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
}
