<?php

// src/Entity/Secteur.php

namespace App\Entity;

use App\Repository\SecteurRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SecteurRepository::class)
 */
class Secteur
{
    /**
 * @ORM\Id
 * @ORM\GeneratedValue(strategy="AUTO")
 * @ORM\Column(type="integer")
 */
private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $nom = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
{
    $this->nom = $nom;
    return $this;
}
}
